<?php

use App\Http\Controllers\Api\CompanyProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ParameterController;

Route::prefix('/')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Front/DefaultScreen');
    });
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');;
    });

    Route::prefix('menu-system')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/MenuSystem');
        });
        Route::get('/getMenu', [MenuController::class, 'getMenu']);
        Route::get('/getMenuGrid', [MenuController::class, 'getMenuGrid']);
        Route::post('/createMenu', [MenuController::class, 'createMenu']);
        Route::post('/updateMenu', [MenuController::class, 'updateMenu']);
        Route::post('/deleteMenu', [MenuController::class, 'deleteMenu']);
    });

    Route::prefix('parameter')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/Parameter');
        });
        Route::get('/getParameter', [ParameterController::class, 'getParameter']);
        Route::post('/create', [ParameterController::class, 'create']);
        Route::post('/update', [ParameterController::class, 'update']);
        Route::post('/delete', [ParameterController::class, 'delete']);
    });

    Route::prefix('company_profile')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/CompanyProfile');
        });
        Route::get('/getCompanyProfile', [CompanyProfileController::class, 'getCompanyProfile']);
        Route::post('/create', [CompanyProfileController::class, 'create']);
        Route::post('/update', [CompanyProfileController::class, 'update']);
        Route::post('/delete', [CompanyProfileController::class, 'delete']);
    });
});
