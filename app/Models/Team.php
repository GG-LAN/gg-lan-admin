<?php
namespace App\Models;

use App\Models\TeamUser;
use App\Observers\TeamObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([TeamObserver::class])]
class Team extends Model
{
    use HasFactory;

    public const NOT_FULL = "not_full";
    public const PENDING = "pending";
    public const REGISTERED = "registered";

    protected $fillable = [
        'name', 'description', 'image', 'tournament_id',
    ];

    protected $with = ['users'];

    // protected $appends = ['captain_id', 'tournament_name', 'team_slots', 'is_full'];
    protected $appends = ['captain', 'captain_id'];

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

    public function getTotalPlacesAttribute()
    {
        return $this->tournament->game->places;
    }

    public function getTeamSlotsAttribute()
    {
        return "{$this->users()->count()}/{$this->getTotalPlacesAttribute()}";
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
}
