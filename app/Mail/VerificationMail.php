<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * User instance
     *
     * @var User
     */
    public $user;

    /**
     * Verification url
     *
     * @var string
     */
    private $url;

    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify.api',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $this->url = config("app.app_front_url_mail_verify") . "?verify_url=" . $verifyUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__("mail.verify"))
                    ->markdown('emails.verification', [
                        "url" => $this->url
                    ]);
    }
}
