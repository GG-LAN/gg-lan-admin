<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CloseTournament extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournament:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close tournament(s) that have an end date less than today';

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

        // Retrieving all tournaments that are open
        $tournaments = Tournament::where("status", "open")->get();
        $countClosedTournaments = 0;


        // If a tournament has a end_date < to today, we assign his status to "finished"
        foreach ($tournaments as $tournament) {
            $tournamentEndDate = new Carbon($tournament->end_date);

            if ($today->greaterThan($tournamentEndDate)) {
                $tournament->status = "finished";
                $tournament->save();

                $countClosedTournaments++;

                $this->log->info("Le tournois " . $tournament->name . " (id: ". $tournament->id .") a été clôturé.");
            }
        }

        // Message info to conclude 
        if ($countClosedTournaments > 0) {
            $this->info($countClosedTournaments . " tournoi(s) ont été clôturé(s).");
        }
        else {
            $this->info("Pas de tournoi(s) à clôturé.");
        }
    }
}
