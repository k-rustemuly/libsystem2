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

                Route::post('accept', [OrganizationBookTransactionController::class, 'accept'])
                    ->name('accept');

            });

    });
