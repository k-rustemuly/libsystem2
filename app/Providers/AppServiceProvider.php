<?php

namespace App\Providers;

use App\Models\Organization;
use App\Models\OrganizationBook;
use App\Models\OrganizationBookInventory;
use App\Models\OrganizationBookTransaction;
use App\Models\OrganizationReader;
use App\Models\ReceivedBook;
use App\Observers\OrganizationBookInventoryObserver;
use App\Observers\OrganizationBookObserver;
use App\Observers\OrganizationBookTransactionObserver;
use App\Observers\OrganizationObserver;
use App\Observers\OrganizationReaderObserver;
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
        Organization::observe(OrganizationObserver::class);
        OrganizationBookTransaction::observe(OrganizationBookTransactionObserver::class);
        OrganizationReader::observe(OrganizationReaderObserver::class);
        OrganizationBookInventory::observe(OrganizationBookInventoryObserver::class);
    }
}
