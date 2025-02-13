<?php

use App\Http\Controllers\API\User\AuthController;
use App\Http\Controllers\API\User\EventController;
use App\Http\Controllers\API\User\OrderController;
use Illuminate\Support\Facades\Route;

/*==========================================
=                user auth                 =
==========================================*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('me', [AuthController::class, 'me']);
/*=====  End of user auth          ======*/

/*===========================
=           events          =
=============================*/
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::post('{event}/purchase', [OrderController::class, 'purchase']);
});
/*=====  End of events   ======*/
