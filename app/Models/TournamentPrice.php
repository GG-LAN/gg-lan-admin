<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Stripe\StripeClient as Stripe;

class TournamentPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price_id', 'price', 'tournament_id', 'type', 'active',
    ];

    // protected $appends = ['stripe_price'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price_id' => 'string',
    ];

    public static function createProduct(array $attributes)
    {
        if (! config("app.stripe_key")) {
            return null;
        }

        $stripe = new Stripe(config("app.stripe_key"));

        $product = $stripe->products->create($attributes);

        return $product;
    }

    public static function create(array $attributes)
    {
        if (! config("app.stripe_key")) {
            return null;
        }

        $stripe = new Stripe(config("app.stripe_key"));

        $price = $stripe->prices->create([
            "nickname"    => $attributes["name"],
            "currency"    => $attributes["currency"],
            "unit_amount" => $attributes["unit_amount"] * 100,
            "product"     => $attributes["product"],
        ]);

        $attributes["price_id"] = $price->id;
        $attributes['price']    = Number::currency($attributes["unit_amount"], in: $attributes["currency"], locale: config('app.locale'));

        // Create row in DB
        $model = static::query()->create($attributes);

        return $model;
    }

    public static function getStripePrice(string $price_id)
    {
        if (! config("app.stripe_key")) {
            return null;
        }

        $stripe = new Stripe(config("app.stripe_key"));

        return $stripe->prices->retrieve($price_id);
    }

    public function getStripePriceAttribute()
    {
        if (! config("app.stripe_key")) {
            return null;
        }

        $stripe = new Stripe(config("app.stripe_key"));

        return $stripe->prices->retrieve($this->price_id);
    }

    public static function archiveTournamentProduct(Tournament $tournament): void
    {
        if (! config("app.stripe_key")) {
            return;
        }

        $stripe = new Stripe(config("app.stripe_key"));

        $priceId = $tournament->currentPrice()->price_id;

        $productId = TournamentPrice::getStripePrice($priceId)->product;

        $stripe->products->update(
            $productId,
            ["active" => false]
        );
    }

    public function tournament()
    {
        return $this->belongsTo("App\Models\Tournament");
    }

    public function purchasedPlaces()
    {
        return $this->hasMany("App\Models\PurchasedPlace");
    }
}
