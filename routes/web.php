<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestSchedulerController;
use App\Http\Controllers\AuthController;

// Vue.js routes
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

// API routes
Route::prefix('api')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    // Protected API routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });
});

// Route::get('/test-scheduler/{user}', [TestSchedulerController::class, 'testScheduler'])
//     ->name('test.scheduler');

