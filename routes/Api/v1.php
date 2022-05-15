<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
*/

Route::post('/register', [\App\Http\Controllers\Api\V1\AuthController::class, 'register'])->name('auth.register');

Route::get('/token', [\App\Http\Controllers\Api\V1\AuthController::class, 'getToken'])->name('auth.token');


Route::group(['middleware' => ['api.v1.token.required', 'api.v1.token.validation.data']], function () {
    Route::get('/whoami', [\App\Http\Controllers\Api\V1\AuthController::class, 'whoAmI'])->name('auth.whoami');
    Route::group(['prefix' => 'url'], function () {
        Route::post('/', [\App\Http\Controllers\Api\V1\UrlController::class, 'create'])->name('url.create');
        Route::get('/all', [\App\Http\Controllers\Api\V1\UrlController::class, 'getAll'])->name('url.all');
        Route::get('/{path}', [\App\Http\Controllers\Api\V1\UrlController::class, 'getSingle'])->name('url.single');
        Route::delete('/{path}', [\App\Http\Controllers\Api\V1\UrlController::class, 'deleteSingle'])->name('url.delete');
    });
});
