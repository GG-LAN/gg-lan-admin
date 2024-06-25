<?php   
namespace App\Models;

use Carbon\Carbon;
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

    public static function getTournaments($numberOfItemsPerPage = 5, $search = null) {
        $query = (new static)->orderBy('created_at', 'desc');

        // If search parameter is given
        if ($search) {
            $query = $query
                    ->where("name",   "like", "%{$search}%");
        }
        
        return $query
        ->paginate($numberOfItemsPerPage)
        ->withQueryString()
        ->through(function($tournament) {
            $startDate = new Carbon($tournament->start_date);
            $startDate = $startDate->format("d/m/Y");
            
            $endDate = new Carbon($tournament->end_date);
            $endDate = $endDate->format("d/m/Y");
            
            return [
                "id"          => $tournament->id,
                "name"        => $tournament->name,
                "description" => $tournament->description,
                "game_id"     => $tournament->game_id,
                "game"        => $tournament->game->name,
                "date"        => $startDate . " | " . $endDate,
                "type"        => $tournament->type == "team" ? "Équipe" : "Solo",
                "places"      => $tournament->places,
                "status"      => $tournament->status,
                "cashprize"   => $tournament->cashprize,
            ];
        });
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