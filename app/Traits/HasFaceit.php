<?php
namespace App\Traits;

use App\Models\FaceitAccount;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

trait HasFaceit
{
    public function faceitAccount(): HasOne
    {
        return $this->hasOne(FaceitAccount::class);
    }

    public function linkFaceitAccount(Collection $data)
    {
        $faceitAccount = null;

        if ($this->faceitAccount) {
            $faceitAccount = $this->faceitAccount;
        } else {
            $faceitAccount = new FaceitAccount();

            $faceitAccount->user()->associate($this);
        }

        $faceitAccount->nickname    = $data["nickname"];
        $faceitAccount->player_id   = $data["player_id"];
        $faceitAccount->steam_id_64 = $data["steam_id_64"];
        $faceitAccount->games       = $data["games"];

        $faceitAccount->save();

        return $faceitAccount;
    }
}
