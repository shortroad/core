<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
*/

Route::post('/register',[\App\Http\Controllers\Api\V1\AuthController::class,'register'])->name('auth.register');
