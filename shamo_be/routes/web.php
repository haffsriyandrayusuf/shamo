<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductCategoryController;

Route::get('/', function () {
    return view('welcome');
});

// ROUTE NAME PREFIXES
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::name('dashboard.')->prefix('dashboard')->group(function () {
        Route::get('/',  function () {
            return view('dashboard');
        })->name('dashboard');
        Route::middleware(['admin'])->group(function () {
            Route::resource('category', ProductCategoryController::class);
        });
    });
});
