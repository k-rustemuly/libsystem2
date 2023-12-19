<?php

use App\Http\Controllers\BookController;
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

        Route::get('books/search', [BookController::class, 'search'])->name('books.search');
    });
