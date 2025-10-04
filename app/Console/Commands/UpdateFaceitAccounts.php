<?php
namespace App\Console\Commands;

use App\Jobs\UpdateFaceitAccount;
use App\Models\FaceitAccount;
use Illuminate\Console\Command;

class UpdateFaceitAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faceit:update-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job for each faceit account to update informations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        FaceitAccount::each(function ($faceitAccount) {
            UpdateFaceitAccount::dispatch($faceitAccount);
        });
    }
}
