<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/getMenu', [MenuController::class, 'getMenu']);
    Route::get('/getMenuGrid', [MenuController::class, 'getMenuGrid']);
    Route::post('/createMenu', [MenuController::class, 'createMenu']);
});
