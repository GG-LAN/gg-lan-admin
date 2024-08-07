<?php

namespace App\Console\Commands;

use App\Models\Tournament;
use Illuminate\Support\Str;
use Illuminate\Support\Number;
use App\Models\TournamentPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTournamentPriceFromStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournamentPrice:updatePriceFromStripe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve the current price from stripe to update the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->log = Log::channel("commands");
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isUpdated = false;
        $tournaments = Tournament::getOpenTournaments();

        foreach ($tournaments as $tournament) {
            $tournamentPrices = $tournament->prices;

            foreach ($tournamentPrices as $tournamentPrice) {
                // Retrieve Stripe price
                $stripePrice = TournamentPrice::getStripePrice($tournamentPrice->price_id);
                
                // Convert to "readable" price string
                $formattedStripePrice = Number::currency(
                    $stripePrice->unit_amount / 100,
                    $stripePrice->currency,
                    config('app.locale')
                );
                
                // If the db price and Stripe price aren't the same, we update the price in db
                if ($tournamentPrice->price !==  $formattedStripePrice) {
                    $tournamentPrice->update([
                        "price" => $formattedStripePrice
                    ]);
                    
                    $isUpdated = true;

                    $this->log->info("[" . $tournamentPrice->name . "] Prix mis à jour: " . $formattedStripePrice);
                }
            }
        }
        
        if ($isUpdated) {
            $this->log->info("--------------------");
        }
    }
}
