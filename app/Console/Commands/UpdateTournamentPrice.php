<?php
namespace App\Console\Commands;

use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTournamentPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournamentPrice:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the active price of a tournament accordingly to his start_date';

    /**
     * Log object
     *
     * @var Log
     */
    private $log;

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
     *
     * @return int
     */
    public function handle()
    {
        $this->log->debug("Lancement de la commande: " . $this->signature);

        $today = Carbon::today();

        $openTournaments = Tournament::where("status", "open")->get();

        foreach ($openTournaments as $tournament) {
            $prices = $tournament->prices;

            // If we have 0 or 1 price, we do nothing
            if (count($prices) <= 1) {
                continue;
            }

            $tournamentStartDate = new Carbon($tournament->start_date);

            // If tournament starts in 6 days or less and the current price is not of type "last_week", we update the prices.active
            if ($today->floatDiffInDays($tournamentStartDate) <= 6) {
                $currentPrice = $tournament->currentPrice();

                if ($currentPrice->type != "last_week") {
                    foreach ($prices as $price) {
                        if ($price->type == "normal") {
                            $price->active = false;
                        } else {
                            $price->active = true;
                        }

                        $price->save();
                    }

                    $this->log->info("Prix mis à jour pour " . $tournament->name . " (id: " . $tournament->id . ")");
                }
            }
        }
    }
}
