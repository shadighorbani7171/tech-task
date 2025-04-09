<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

Route::prefix('v1')->group(function () {
    // Auth routes with JWT
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
    
    // Protected auth routes
    Route::middleware('auth:api')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('auth/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');
    });

    // User management routes
    Route::get('countries', [UserController::class, 'countries'])->name('countries.list');
    
    // Protected user routes
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('users', UserController::class);
    });
}); 