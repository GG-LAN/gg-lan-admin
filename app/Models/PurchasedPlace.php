<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tournament;

class PurchasedPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tournament_price_id'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    function tournamentPrice() {
        return $this->belongsTo('App\Models\TournamentPrice');
    }

    public function tournament() {
        return Tournament::findOrFail($this->tournamentPrice->tournament_id);
    }
}
