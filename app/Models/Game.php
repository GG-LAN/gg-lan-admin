<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model {
    use HasFactory;

    protected $fillable = [
        "name", "description", "places", "image"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    protected $appends = [];

    public function teams() {
        return $this->hasMany('App\Models\Team');
    }

    public function tournaments() {
        return $this->hasMany('App\Models\Tournament');
    }

    public static function getGames($numberOfItemsPerPage = 5, $search = null) {
        $query = (new static);

        // If search parameter is given
        if ($search) {            
            $query = $query->whereAny([
                "name",
                "places",
            ], "like", "%{$search}%");
        }
        
        return $query
        ->paginate($numberOfItemsPerPage)
        ->withQueryString()
        ->through(function($game) {
            return [
                "id"        => $game->id,
                "name"      => $game->name,
                "places"    => $game->places,
                "game_type" => $game->places > 1 ? "Équipe" : "Solo",
            ];
        });
    }
}