<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PurgeUnverifiedAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:purge-unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete account that has been created since at least 1 year and are unverified';

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

        $users = User::where('email_verified_at', null)->get();
        $countDeletedAccounts = 0;

        // If createdAt + 1 year is inferior to today, 
        // it means that the account has been created for at least 1 year and need to be deleted
        foreach ($users as $user) {
            $this->info($user->created_at);
            $createdAt = new Carbon($user->created_at);
            $createdAtPlusOneYear = $createdAt->addYear();

            $this->info($createdAtPlusOneYear);

            // If the account exist for more than 1 year, delete the account
            if ($today->greaterThan($createdAtPlusOneYear)) {
                // If there is an access token related to the account, delete the token
                $accessToken = AccessToken::where("tokenable_id", $user->id)->first();
                if ($accessToken) {
                    $accessToken->delete();
                }

                // Delete the account
                $user->delete();

                $this->log->info("Le compte de " . $user->pseudo . " (id: ". $user->id .") a bien été supprimé.");

                $countDeletedAccounts++;
            }
        }

        // Message info to conclude 
        if ($countDeletedAccounts > 0) {
            $this->info($countDeletedAccounts . " compte(s) ont été supprimé(s).");
        }
        else {
            $this->info("Pas de compte à supprimé.");
        }
    }
}
