<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/visitor-count', [UserController::class, 'visitorCount']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('articles', ArticleController::class);
    Route::apiResource('destinations', DestinationController::class);

    Route::post('/articles/{article}/rate', [ArticleController::class, 'rate']);
    Route::post('/articles/{article}/comment', [ArticleController::class, 'comment']);
    Route::post('/destinations/{destination}/rate', [DestinationController::class, 'rate']);
    Route::post('/destinations/{destination}/comment', [DestinationController::class, 'comment']);

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole']);
    });
});

// Fallback route for undefined routes
Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
});

