<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participation extends Model
{
    use HasFactory;

    protected $fillable = [
        "status",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public static function register(User $user, Tournament $tournament, ?Team $team = null): Participation
    {
        return self::updateOrCreate(
            [
                "user_id"       => $user->id,
                "tournament_id" => $tournament->id,
            ],
            [
                "team_id" => $team ? $team->id : null,
                "status"  => $team ? $team->registration_state : "registered",
            ]
        );
    }

    public static function unregister(User $user, Tournament $tournament, ?Team $team = null): void
    {
        $participation = self::query()
            ->where("user_id", $user->id)
            ->where("tournament_id", $tournament->id)
            ->when($team, function (Builder $query) use ($team) {
                $query->where("team_id", $team->id);
            })
            ->first();

        $participation->delete();
    }
}
