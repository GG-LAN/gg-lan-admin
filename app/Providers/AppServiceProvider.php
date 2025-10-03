<?php
namespace App\Providers;

use App\Models\User;
use App\Providers\Socialite\FaceitProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Collection::macro('paginate', function ($perPage = 5) {
            $page = LengthAwarePaginator::resolveCurrentPage('page');

            return new LengthAwarePaginator($this->forPage($page, $perPage), $this->count(), $perPage, $page, [
                'path'  => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query(),
            ]);
        });

        $this->configureScramble();

        $this->configureSocialite();
    }

    private function configureScramble(): void
    {
        Gate::define('viewApiDocs', function (User $user) {
            return $user->isAdmin();
        });
    }

    private function configureSocialite(): void
    {
        Socialite::extend('faceit', function ($app) {
            return Socialite::buildProvider(FaceitProvider::class, config("services.faceit"));
        });
    }
}
