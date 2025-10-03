<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class Faceit
{
    public function __construct(
        private $base_url = "https://open.faceit.com/data/v4"
    ) {}

    public static function getPlayerByNickname(string $nickname)
    {
        $faceit = new self();

        $response = Http::withHeaders([
            "Authorization" => "Bearer " . config("services.faceit.api_key"),
        ])
            ->get($faceit->base_url . "/players", [
                "nickname" => $nickname,
            ]);

        $result = $response->collect();

        if ($result->has("errors")) {
            return null;
        }

        return $result;
    }
}
