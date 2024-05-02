<?php   
namespace App\Models;

use App\Models\Team;
use Illuminate\Http\Request;
use Stripe\StripeClient as Stripe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends Model {
    use HasFactory;
    
    protected $fillable = [
        'name', 'description', 'game_id', 'start_date', 'end_date', 'places', 'cashprize', 'status', 'image', 'type'
    ];

    // protected $with = ['teams', 'players'];

    protected $appends = ['register_count', 'isFull'];

    public function game() {
        return $this->belongsTo('App\Models\Game');
    }

    public function teams() {
        return $this->hasMany('App\Models\Team');
    }

    public function players() {
        return $this->belongsToMany('App\Models\User');
    }

    public function prices() {
        return $this->hasMany("App\Models\TournamentPrice");
    }
    
    public function currentPrice() {
        return $this->prices()->where("active", true)->first();
    }

    public function purchasedPlaces() {
        if (!$this->currentPrice()) {
            return [];
        }
        
        return $this->currentPrice()->purchasedPlaces();
    }

    public function getRegisterCountAttribute() {
        if ($this->type == "solo") {
            return $this->players()->count();
        }

        return $this->teams()->where("registration_state", Team::REGISTERED)->count();        
    }

    public function getIsFullAttribute() {
        return $this->getRegisterCountAttribute() == $this->places ? true:false;
    }

    public function getPaymentLink(Request $request): String {
        $stripe = new Stripe(config("app.stripe_api_key"));

        $session = $stripe->checkout->sessions->create([
            "success_url" => $request->success_url,
            "cancel_url" => $request->cancel_url,
            "mode" => "payment",
            "line_items" => [[
                "price" => $this->currentPrice()->price_id,
                "quantity" => 1
            ]]
        ]);

        return $session["url"];
    }
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cashprize' => 'string',
    ];

}