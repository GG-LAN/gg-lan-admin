<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceitAccount extends Model
{
    protected $fillable = [
        "nickname", "player_id", "steam_id_64", "games",
    ];

    protected $casts = [
        "games" => "array",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
