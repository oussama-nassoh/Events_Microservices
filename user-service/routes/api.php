<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Route;


// Health check endpoint
Route::get('health', function () {
    return response()->json([
        'status' => 'healthy',
        'service' => 'user',
        'timestamp' => now(),
        'port' => env('PORT', 8002)
    ]);
});

// Public routes
Route::post('/', [UserController::class, 'store']);

// Protected routes
Route::middleware(CheckUserRole::class)->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/by-email/{email}', [UserController::class, 'findByEmail']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});
