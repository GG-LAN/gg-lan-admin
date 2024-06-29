<?php

namespace App\Models;

use App\Models\Tournament;
use App\Models\PurchasedPlace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchasedPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tournament_price_id'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    function tournamentPrice() {
        return $this->belongsTo('App\Models\TournamentPrice');
    }

    public function tournament() {
        return Tournament::findOrFail($this->tournamentPrice->tournament_id);
    }

    public static function checkExist(User $user, Tournament $tournament) {
        return self::
              where("user_id", $user->id)
            ->where("tournament_price_id", $tournament->currentPrice()->id)
            ->exists();
    }
}
