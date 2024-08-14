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
}