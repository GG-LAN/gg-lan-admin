<?php
namespace App\Models;

use App\Models\Tournament;
use App\Models\TournamentPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchasedPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tournament_id', 'paid',
    ];

    protected $casts = [
        'paid' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tournamentPrice(): BelongsTo
    {
        return $this->belongsTo('App\Models\TournamentPrice');
    }

    public function tournament(): ?BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public static function forOpenTournaments()
    {
        return (new static )->whereRelation('tournamentPrice.tournament', 'status', 'open');
    }

    public static function register(User $user, Tournament $tournament, $paid = false)
    {
        $payment = self::firstOrNew([
            "user_id"       => $user->id,
            "tournament_id" => $tournament->id,
        ]);

        if ($paid) {
            $price = $tournament->currentPrice();

            $payment->tournamentPrice()->associate($price);
            $payment->paid = $paid;
        }

        $payment->save();

        return $payment;
    }

    public static function unregister(User $user, Tournament $tournament)
    {
        $payment = self::query()
            ->where("user_id", $user->id)
            ->where("tournament_id", $tournament->id)
            ->first();

        if ($payment && ! $payment->paid) {
            return $payment->delete();
        }
    }
}
