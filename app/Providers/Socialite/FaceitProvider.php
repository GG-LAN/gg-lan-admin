<?php
namespace App\Providers\Socialite;

use GuzzleHttp\RequestOptions;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class FaceitProvider extends AbstractProvider
{
    protected $scopeSeparator = ' ';

    protected $scopes = ['openid'];

    protected $usesPKCE = true;

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://cdn.faceit.com/widgets/sso/index.html',
            $state
        );
    }

    protected function buildAuthUrlFromBase($url, $state)
    {
        return $url
        . '?'
        . http_build_query($this->getCodeFields($state), '', '&', $this->encodingType)
        . '&redirect_popup=true'
        . '&code_verifier=' . $this->getCodeVerifier();
    }

    protected function getTokenUrl()
    {
        return 'https://api.faceit.com/auth/v1/oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://api.faceit.com/auth/v1/resources/userinfo',
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

        return json_decode((string) $response->getBody(), true);
    }

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::FORM_PARAMS => $this->getTokenFields($code),
            RequestOptions::HEADERS     => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['guid'],
            'nickname' => $user['nickname'],
        ]);
    }
}
