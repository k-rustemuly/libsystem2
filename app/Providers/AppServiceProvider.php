<?php

namespace App\Providers;

use App\Models\OrganizationBook;
use App\Models\ReceivedBook;
use App\Observers\OrganizationBookObserver;
use App\Observers\ReceivedBookObserver;
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
        OrganizationBook::observe(OrganizationBookObserver::class);
        ReceivedBook::observe(ReceivedBookObserver::class);
    }
}
