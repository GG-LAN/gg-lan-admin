<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceitAccount extends Model
{
    protected $fillable = [
        "user_id", "nickname", "player_id", "steam_id_64", "elo_cs2",
    ];

    protected $appends = ["steam_page_url"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function steamPageUrl(): Attribute
    {
        $steamPageUrl = null;

        if ($this->steam_id_64) {
            $steamPageUrl = "https://steamcommunity.com/profiles/{$this->steam_id_64}";
        }

        return Attribute::make(
            get: fn() => $steamPageUrl
        );
    }
}
