<?php
namespace App\Traits;

use App\Models\FaceitAccount;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait HasFaceit
{
    public function faceitAccount(): HasOne
    {
        return $this->hasOne(FaceitAccount::class);
    }

    public function linkFaceitAccount(Collection $data)
    {
        $faceitAccount = FaceitAccount::firstOrNew(
            ["user_id" => $this->id],
            ["user_id" => $this->id]
        );

        $faceitAccount->nickname    = $data["nickname"];
        $faceitAccount->player_id   = $data["player_id"];
        $faceitAccount->steam_id_64 = $data["steam_id_64"];

        if (Arr::has($data["games"], "cs2")) {
            $faceitAccount->elo_cs2 = $data["games"]["cs2"]["faceit_elo"];
        } else {
            $faceitAccount->elo_cs2 = 0;
        }

        $faceitAccount->save();

        return $faceitAccount;
    }
}
