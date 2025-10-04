<?php
namespace App\Jobs;

use App\Models\FaceitAccount;
use App\Services\Faceit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateFaceitAccount implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public FaceitAccount $faceitAccount
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $faceitAccount = Faceit::getPlayerById($this->faceitAccount->player_id);

        if (! $faceitAccount) {
            Log::info("Faceit account not found: {$this->faceitAccount->nickname} ({$this->faceitAccount->player_id})");

            return;
        }

        $this->faceitAccount->user->linkFaceitAccount($faceitAccount->only([
            "player_id",
            "nickname",
            "steam_id_64",
            "games",
        ]));
    }
}
