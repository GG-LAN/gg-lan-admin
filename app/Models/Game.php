<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "description", "places", "image",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    protected $appends = [];

    public function scopeTeamGame(Builder $query)
    {
        $query->where("places", ">", 1);
    }

    public function scopeSoloGame(Builder $query)
    {
        $query->where("places", "=", 1);
    }

    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    public function tournaments()
    {
        return $this->hasMany('App\Models\Tournament');
    }
}
