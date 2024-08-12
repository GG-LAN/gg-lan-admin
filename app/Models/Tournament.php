<?php   
namespace App\Models;

use Carbon\Carbon;
use App\Models\Team;
use App\Helpers\Table;
use App\Models\Setting;
use Illuminate\Http\Request;
use Stripe\StripeClient as Stripe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends Model {
    use HasFactory;
    
    protected $fillable = [
        'name', 'description', 'game_id', 'start_date', 'end_date', 'places', 'cashprize', 'status', 'image', 'type'
    ];

    protected $with = ['teams', 'players'];

    protected $appends = ['register_count', 'isFull', 'price'];

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
        $purchasedPlaces = [];
        
        foreach ($this->prices as $price) {
            foreach ($price->purchasedPlaces as $purchasedPlace) {
                array_push($purchasedPlaces, $purchasedPlace);
            }
        }
        
        return $purchasedPlaces;
    }

    public function checkPlayerIsRegistered(User $user) {
        return $this->players()->where('user_id', $user->id)->exists();
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

    public function getPriceAttribute() {
        if ($this->currentPrice()) {
            return $this->currentPrice()->price;
        }

        return null;
    }

    public function getPaymentLink(Request $request): String {
        if (!Setting::get('stripe_api_key')) {
            return null;
        }
        
        $stripe = new Stripe(Setting::get('stripe_api_key'));

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

    public static function tableSearch($itemsPerPage = 5, $search = null, $sort = null) {
        $query = (new static);

        
        // If search parameter is given
        if ($search) {
            $query = $query->whereAny([
                "name"
            ], "like", "%{$search}%");
        }
        
        // If sort parameter is given
        if ($sort) {
            $query = $query->orderBy(
                explode(",", $sort)[0],
                explode(",", $sort)[1]
            );
        }
        // Sort by desc created_at by default
        else {
            $query = $query->orderBy('created_at', 'desc');
        }
        
        return $query
        ->paginate($itemsPerPage)
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

    public static function table($itemsPerPage = 5, $search = null, $sort = null) {
        $table = new Table;

        $table->addTextColumn("name", "Nom", true);
        $table->addTextColumn("game", "Jeu");
        $table->addTextColumn("date", "Dates Début | Fin");
        $table->addTextColumn("type", "Type", true);
        $table->addTextColumn("places", "Places", true);
        $table->addTextColumn("cashprize", "Cashprize (€)", true);
        $table->addBadgeColumn("status", "Statut", sortable: true, badges: [
            Table::Badge("closed", "Fermé", "red"),
            Table::Badge("finished", "Terminé", "orange"),
            Table::Badge("open", "Ouvert", "green")
        ],);
        
        if ($sort) {
            $table->addSort($sort);
        }

        $table->can("search");
        $table->can("create");
        $table->can("delete");
        $table->can("show", "tournaments.show");

        $table->setData((new static)->tableSearch(search: $search, sort: $sort));

        return $table->generate();
    }

    public function getPayments() {
        $paymentList = [];

        foreach ($this->purchasedPlaces() as $purchasedPlace) {
            array_push($paymentList, [
                "id" => $purchasedPlace->user->id,
                "pseudo" => $purchasedPlace->user->pseudo,
                "tournament_id" => $this->id,
                "tournament_name" => $this->name,
                "status" => "paid"
            ]);
        }
        
        $paidPlayersIds = array_column($paymentList, 'id');
    
        if ($this->type == "team") {
            foreach ($this->teams as $team) {
                foreach ($team->users as $player) {
                    if(!in_array($player->id, $paidPlayersIds)) {
                        array_push($paymentList, [
                            "id" => $player->id,
                            "pseudo" => $player->pseudo,
                            "tournament_id" => $this->id,
                            "tournament_name" => $this->name,
                            "status" => "not_paid"
                        ]);
                    }
                }
            }
        }
        else {
            foreach ($this->players as $player) {
                if(!in_array($player->id, $paidPlayersIds)) {
                    array_push($paymentList, [
                        "id" => $player->id,
                        "pseudo" => $player->pseudo,
                        "tournament_id" => $this->id,
                        "tournament_name" => $this->name,
                        "status" => "not_paid"
                    ]);
                }
            }
        }

        return collect($paymentList);        
    }

    public static function getOpenTournaments() {
        return (new static)->where('status', 'open')->get();
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