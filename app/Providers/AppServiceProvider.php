<?php
namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
    }

    private function configureScramble(): void
    {
        Gate::define('viewApiDocs', function (User $user) {
            return $user->isAdmin();
        });
    }
}
