<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\MenuController;


Route::prefix('/')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Default/DefaultScreen');
    });
    Route::get('/getDashboard', [MenuController::class, 'getGrid']);
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        });
    });

    Route::prefix('menu-system')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/MenuSystem');
        });
    });
});
