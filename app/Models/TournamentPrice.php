<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Support\Number;
use Stripe\StripeClient as Stripe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price_id', 'price', 'tournament_id', 'type', 'active'
    ];

    protected $appends = ['stripe_price'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price_id'    => 'string',
    ];

    public static function createProduct(array $attributes) {
        if (!Setting::get('stripe_api_key')) {
            return null;
        }

        $stripe = new Stripe(Setting::get('stripe_api_key'));

        $product = $stripe->products->create($attributes);

        return $product;
    }

    public static function create(array $attributes) {
        if (!Setting::get('stripe_api_key')) {
            return null;
        }
        
        $stripe = new Stripe(Setting::get('stripe_api_key'));

        $price = $stripe->prices->create([
            "nickname"    => $attributes["name"],
            "currency"    => $attributes["currency"],
            "unit_amount" => $attributes["unit_amount"],
            "product"     => $attributes["product"]
        ]);

        $attributes["price_id"] = $price->id;
        $attributes['price'] = Number::currency($attributes["unit_amount"] / 100, in: $attributes["currency"], locale: config('app.locale'));       
        
        // Create row in DB
        $model = static::query()->create($attributes);

        return $model;
    }

    public static function getStripePrice(string $price_id) {
        if (!Setting::get('stripe_api_key')) {
            return null;
        }
        
        $stripe = new Stripe(Setting::get('stripe_api_key'));

        return $stripe->prices->retrieve($price_id);
    }

    public function getStripePriceAttribute() {
        if (!Setting::get('stripe_api_key')) {
            return null;
        }
            
        $stripe = new Stripe(Setting::get('stripe_api_key'));

        return $stripe->prices->retrieve($this->price_id);
    }

    public function tournament() {
        return $this->belongsTo("App\Models\Tournament");
    }

    public function purchasedPlaces() {
        return $this->hasMany("App\Models\PurchasedPlace");
    }
}
