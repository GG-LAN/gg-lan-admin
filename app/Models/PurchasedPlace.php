<?php
namespace App\Models;

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

    protected $appends = ["tournament_id"];

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

    public function getTournamentIdAttribute()
    {
        return $this->tournament()->without(["teams", "players"])->first()->id;
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

        $payment = self::firstOrNew([
            "user_id"             => $user->id,
            "tournament_price_id" => $price->id,
        ]);

        if (! $payment->paid) {
            $payment->paid = $paid;
        }

        $payment->save();

        return $payment;
    }

    public static function unregister(User $user, Tournament $tournament)
    {
        $payment = self::where("user_id", $user->id)
            ->where("tournament_price_id", $tournament->currentPrice()->id)
            ->first();

        if ($payment && ! $payment->paid) {
            return $payment->delete();
        }
    }
}
