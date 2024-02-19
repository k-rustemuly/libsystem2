<?php

use App\Http\Controllers\BookController;
use App\MoonShine\Controllers\OrganizationBookTransactionController;
use App\MoonShine\Controllers\OrganizationChooseController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::prefix('choose')
            ->as('choose.')
            ->controller(OrganizationChooseController::class)
            ->group(function () {

                Route::get('to/{admin}', 'to')->name('to');

            });

        Route::prefix('books')
            ->as('books.')
            ->group(function () {

                Route::get('search', [BookController::class, 'search'])->name('search');

                Route::get('search/inventory', [BookController::class, 'searchByInventory'])->name('search.inventory');

                Route::post('accept', [OrganizationBookTransactionController::class, 'accept'])
                    ->name('accept');

                Route::post('mass-receive', [OrganizationBookTransactionController::class, 'massReceive'])
                    ->name('mass.receive');
            });

    });
