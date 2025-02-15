<?php
namespace App\Models;

use App\Models\TeamUser;
use App\Models\Tournament;
use App\Observers\TeamObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([TeamObserver::class])]
class Team extends Model
{
    use HasFactory, Notifiable;

    public const NOT_FULL   = "not_full";
    public const PENDING    = "pending";
    public const REGISTERED = "registered";

    protected $fillable = [
        'name', 'description', 'image', 'tournament_id',
    ];

    protected $with = ['users'];

    protected $appends = ['captain', 'captain_id', 'team_slots'];

    public function scopeRegistered(Builder $query): void
    {
        $query->where("registration_state", "registered");
    }

    public function scopePending(Builder $query): void
    {
        $query->where("registration_state", "pending");
    }

    public function scopeNotFull(Builder $query): void
    {
        $query->where("registration_state", "not_full");
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User')
            ->using(TeamUser::class)
            ->withPivot('captain')
            ->withTimestamps();
    }

    public function getCaptainAttribute()
    {
        return $this->users()->where('captain', true)->first();
    }

    public function getCaptainIdAttribute()
    {
        return $this->getCaptainAttribute()->id;
    }

    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }

    public function getTotalPlacesAttribute(): int
    {
        return Tournament::where("id", $this->tournament_id)->first()->game->places;
    }

    public function getTeamSlotsAttribute(): string
    {
        return "{$this->users()->count()} / {$this->getTotalPlacesAttribute()}";
    }

    public function getIsFullAttribute()
    {
        $isFull = ($this->getTotalPlacesAttribute() == $this->users()->count()) ? true : false;
        return "{$isFull}";
    }

    public function getTournamentNameAttribute()
    {
        return "{$this->tournament->name}";
    }

    public function playersQuery(): Builder
    {
        return TeamUser::query()
            ->where("team_id", $this->id);
    }
}
