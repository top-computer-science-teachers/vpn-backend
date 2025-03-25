<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VpnController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/create-user', [UserController::class, 'createUser']);

Route::prefix('auth:sanctum')->group(function () {

    Route::get('/get-user', [UserController::class, 'getUser']);

    Route::get('/get-demo', [VpnController::class, 'getDemo']);
});
