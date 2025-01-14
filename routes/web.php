<?php

use App\Http\Controllers\Api\AboutNewsTypeController;
use App\Http\Controllers\Api\CompanyProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\NewsInformationController;
use App\Http\Controllers\Api\NewsTypeController;
use App\Http\Controllers\Api\ParameterController;
use App\Http\Controllers\Api\SponsorController;

Route::prefix('/')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Front/DefaultScreen');
    });
    Route::get('/getCompanyProfile', [CompanyProfileController::class, 'getCompanyProfile']);
    Route::get('/getNewsTypeGrid', [NewsTypeController::class, 'getNewTypeGrid']);
    Route::get('/getNewsInformation', [NewsInformationController::class, 'getNewsInformationGrid']);
    Route::get('/getAboutNewsType', [AboutNewsTypeController::class, 'getAboutNewsType']);
    Route::get('/getSponsor', [SponsorController::class, 'getSponsorGrid']);
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');;
    });

    Route::prefix('menu_system')->group(function () {
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

    Route::prefix('news_type')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/NewsType');
        });
        Route::get('/getNewsTypeGrid', [NewsTypeController::class, 'getNewTypeGrid']);
        Route::post('/create', [NewsTypeController::class, 'create']);
        Route::post('/update', [NewsTypeController::class, 'update']);
        Route::post('/delete', [NewsTypeController::class, 'delete']);
    });

    Route::prefix('news_information')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/NewsInformation');
        });
        Route::get('/getNewsInformationGrid', [NewsInformationController::class, 'getNewsInformationGrid']);
        Route::post('/create', [NewsInformationController::class, 'create']);
        Route::post('/update', [NewsInformationController::class, 'update']);
        Route::post('/delete', [NewsInformationController::class, 'delete']);
    });

    Route::prefix('about_news_type')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/AboutNewsType');
        });
        Route::get('/getAboutNewsType', [AboutNewsTypeController::class, 'getAboutNewsType']);
        Route::post('/create', [AboutNewsTypeController::class, 'create']);
        Route::post('/update', [AboutNewsTypeController::class, 'update']);
        Route::post('/delete', [AboutNewsTypeController::class, 'delete']);
    });

    Route::prefix('sponsor')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Components/Sponsor');
        });
        Route::get('/getSponsorGrid', [SponsorController::class, 'getSponsorGrid']);
        Route::post('/create', [SponsorController::class, 'create']);
        Route::post('/update', [SponsorController::class, 'update']);
        Route::post('/delete', [SponsorController::class, 'delete']);
    });
});
