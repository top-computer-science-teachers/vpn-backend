<?php

use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/create-user', [UserController::class, 'createUser']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/get-user', [UserController::class, 'getUser']);

    Route::prefix('connections')->group(function () {
        Route::get('/get', [ConnectionController::class, 'getConnection']);
        Route::post('/demo', [ConnectionController::class, 'createDemoConnection']);
        Route::post('/create', [ConnectionController::class, 'createConnection']);
        Route::post('/cancel', [ConnectionController::class, 'cancelConnection']);
    });

});
