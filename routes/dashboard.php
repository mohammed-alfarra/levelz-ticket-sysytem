<?php

use App\Http\Controllers\API\Dashboard\AuthController;
use Illuminate\Support\Facades\Route;

/*===================================
=            admin login           =
===================================*/

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout']);
Route::post('me', [AuthController::class, 'me']);
/*=====  End of admin login  ======*/
