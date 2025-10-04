<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class Faceit
{
    public static function getPlayerByNickname(string $nickname)
    {
        $url = config("services.faceit.api_url");

        $response = Http::withHeaders([
            "Authorization" => "Bearer " . config("services.faceit.api_key"),
        ])
            ->get($url . "/players", [
                "nickname" => $nickname,
            ]);

        $result = $response->collect();

        if ($result->has("errors")) {
            return null;
        }

        return $result;
    }

    public static function getPlayerById(string $player_id)
    {
        $url = config("services.faceit.api_url");

        $response = Http::withHeaders([
            "Authorization" => "Bearer " . config("services.faceit.api_key"),
        ])
            ->get($url . "/players/{$player_id}");

        $result = $response->collect();

        if ($result->has("errors")) {
            return null;
        }

        return $result;
    }
}
