<?php

namespace App\Models;

use App\Models\PurchasedPlace;
use App\Models\Tournament;
use App\Models\TournamentPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tournament_price_id', 'paid',
    ];

    protected $casts = [
        'paid' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tournamentPrice()
    {
        return $this->belongsTo('App\Models\TournamentPrice');
    }

    public function tournament()
    {
        return $this->tournamentPrice->tournament();
    }

    public static function forOpenTournaments()
    {
        return (new static )->whereRelation('tournamentPrice.tournament', 'status', 'open');
    }

    public static function checkExist(User $user, Tournament $tournament)
    {
        return self::
            where("user_id", $user->id)
            ->where("tournament_price_id", $tournament->currentPrice()->id)
            ->exists();
    }

    public static function register(User $user, Tournament $tournament, $paid = false)
    {
        $price = $tournament->currentPrice();

        $payment = self::updateOrCreate(
            ["user_id" => $user->id, "tournament_price_id" => $price->id],
            ["paid" => $paid]
        );

        return $payment;
    }

    public static function unregister(User $user, Tournament $tournament)
    {
        $payment = (new static )->firstOrCreate([
            "user_id" => $user->id,
            "tournament_price_id" => $tournament->currentPrice()->id,
        ]);

        return $payment->delete();
    }
}
