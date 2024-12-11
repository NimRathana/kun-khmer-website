<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\demoController;


Route::prefix('/')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Default/DefaultScreen');
    });
    Route::get('/getDashboard', [demoController::class, 'getGrid']);
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        });
        Route::get('/getGrid', [demoController::class, 'getGrid']);
    });

    Route::prefix('sale')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/index');
        });
    });
});
