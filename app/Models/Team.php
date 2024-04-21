<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model {
    use HasFactory;

    public const NOT_FULL = "not_full";
    public const PENDING  = "pending";
    public const REGISTERED = "registered";
    
    protected $fillable = [
        'name', 'description', 'image', 'tournament_id',
    ];

    protected $with = ['users'];

    protected $appends = ['captain_id'];

    public function users() {
        return $this->belongsToMany('App\Models\User')->withPivot('captain');
    }

    public function getCaptainIdAttribute() {
        return $this->users()->where('captain', true)->first()->id;
    }

    public function tournament() {
        return $this->belongsTo('App\Models\Tournament');
    }

    public function getTotalPlacesAttribute() {
        return $this->tournament->game->places;
    }

    public function getIsFullAttribute() {
        return ($this->getTotalPlacesAttribute() == $this->users()->count()) ? true : false;
    }
}