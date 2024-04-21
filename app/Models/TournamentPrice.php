<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\StripeClient as Stripe;

class TournamentPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price_id', 'tournament_id', 'type', 'active'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price_id'    => 'string',
    ];

    public static function createProduct(array $attributes) {
        $stripe = new Stripe(config("app.stripe_api_key"));

        $product = $stripe->products->create($attributes);

        return $product;
    }

    public static function create(array $attributes) {
        $stripe = new Stripe(config("app.stripe_api_key"));

        $price = $stripe->prices->create([
            "nickname"    => $attributes["name"],
            "currency"    => $attributes["currency"],
            "unit_amount" => $attributes["unit_amount"],
            "product"     => $attributes["product"]
        ]);

        $attributes["price_id"] = $price->id;

        // Create row in DB
        $model = static::query()->create($attributes);

        return $model;
    }

    public static function getStripePrice(string $price_id) {
        $stripe = new Stripe(config("app.stripe_api_key"));

        return $stripe->prices->retrieve($price_id);
    }

    public function tournament() {
        return $this->belongsTo("App\Models\Tournament");
    }

    public function purchasedPlaces() {
        return $this->hasMany("App\Models\PurchasedPlace");
    }
}
