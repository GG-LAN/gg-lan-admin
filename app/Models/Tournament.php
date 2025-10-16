<?php
namespace App\Models;

use App\Models\Team;
use App\Observers\TournamentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Stripe\StripeClient as Stripe;

#[ObservedBy([TournamentObserver::class])]
class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'game_id', 'start_date', 'end_date', 'places', 'cashprize', 'status', 'image', 'type', 'external_url',
    ];

    protected $appends = ['register_count', 'isFull', 'price'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cashprize' => 'string',
    ];

    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    public function players()
    {
        return $this->belongsToMany('App\Models\User')
            ->using(TournamentUser::class)
            ->withTimestamps();
    }

    public function prices()
    {
        return $this->hasMany("App\Models\TournamentPrice");
    }

    public function currentPrice()
    {
        return $this->prices()->where("active", true)->first();
    }

    public function purchasedPlaces()
    {
        return $this->hasManyThrough(PurchasedPlace::class, TournamentPrice::class);
    }

    public function checkPlayerIsRegistered(User $user)
    {
        return $this->players()->where('user_id', $user->id)->exists();
    }

    public function getRegisterCountAttribute()
    {
        if ($this->type == "solo") {
            return $this->players()->count();
        }

        return $this->teams()->where("registration_state", Team::REGISTERED)->count();
    }

    public function getIsFullAttribute()
    {
        return $this->getRegisterCountAttribute() == $this->places ? true : false;
    }

    public function getPriceAttribute()
    {
        if ($this->currentPrice()) {
            return $this->currentPrice()->price;
        }

        return null;
    }

    public function getPaymentLink(Request $request): ?string
    {
        if (! config("app.stripe_key")) {
            return null;
        }

        $stripe = new Stripe(config("app.stripe_key"));

        $session = $stripe->checkout->sessions->create([
            "success_url" => $request->success_url,
            "cancel_url"  => $request->cancel_url,
            "mode"        => "payment",
            "line_items"  => [[
                "price"    => $this->currentPrice()->price_id,
                "quantity" => 1,
            ]],
        ]);

        return $session["url"];
    }

    public function getPayments()
    {
        $paymentList = [];

        foreach ($this->purchasedPlaces() as $purchasedPlace) {
            array_push($paymentList, [
                "id"              => $purchasedPlace->user->id,
                "pseudo"          => $purchasedPlace->user->pseudo,
                "tournament_id"   => $this->id,
                "tournament_name" => $this->name,
                "status"          => "paid",
            ]);
        }

        $paidPlayersIds = array_column($paymentList, 'id');

        if ($this->type == "team") {
            foreach ($this->teams as $team) {
                foreach ($team->users as $player) {
                    if (! in_array($player->id, $paidPlayersIds)) {
                        array_push($paymentList, [
                            "id"              => $player->id,
                            "pseudo"          => $player->pseudo,
                            "tournament_id"   => $this->id,
                            "tournament_name" => $this->name,
                            "status"          => "not_paid",
                        ]);
                    }
                }
            }
        } else {
            foreach ($this->players as $player) {
                if (! in_array($player->id, $paidPlayersIds)) {
                    array_push($paymentList, [
                        "id"              => $player->id,
                        "pseudo"          => $player->pseudo,
                        "tournament_id"   => $this->id,
                        "tournament_name" => $this->name,
                        "status"          => "not_paid",
                    ]);
                }
            }
        }

        return collect($paymentList);
    }

    public function paymentsQuery(): Builder
    {
        return PurchasedPlace::query()
            ->where("tournaments.id", $this->id)
            ->join("tournament_prices", "tournament_prices.id", "=", "purchased_places.tournament_price_id")
            ->join("tournaments", "tournaments.id", "=", "tournament_prices.tournament_id");
    }

    public static function allPaymentsQuery(string $status = "open"): Builder
    {
        return PurchasedPlace::query()
            ->where("tournaments.status", $status)
            ->join("tournament_prices", "tournament_prices.id", "=", "purchased_places.tournament_price_id")
            ->join("tournaments", "tournaments.id", "=", "tournament_prices.tournament_id");
    }

    public function teamsQuery($registrationState = "registered"): Builder
    {
        return self::query()
            ->where("tournaments.id", $this->id)
            ->where("teams.registration_state", $registrationState)
            ->join("teams", "teams.tournament_id", "=", "tournaments.id");
    }

    public function playersQuery(): Builder
    {
        return self::query()
            ->where("tournaments.id", $this->id)
            ->join("tournament_user", "tournament_user.tournament_id", "=", "tournaments.id");
    }

    public static function getOpenTournaments()
    {
        return (new static )->where('status', 'open')->get();
    }

    public function teamsNotFull(): Collection
    {
        return $this->teams->where("registration_state", "!=", Team::REGISTERED);
    }

    public function delete()
    {
        TournamentPrice::archiveTournamentProduct($this);

        return parent::delete();
    }
}
