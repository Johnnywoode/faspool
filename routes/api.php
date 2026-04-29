<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API endpoints (if any)
Route::get('/ping', function() {
    return response()->json(['status' => 'ok', 'timestamp' => now()->toISOString()]);
});

// Protected API routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Order endpoints
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'purchase']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::get('/orders/{id}/check', [OrderController::class, 'checkSms']);

    // Wallet endpoints
    Route::get('/wallet/balance', [WalletController::class, 'balance']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
    Route::post('/wallet/deposit', [WalletController::class, 'deposit']);

    // User profile
    Route::get('/me', function() {
        $user = auth()->user();
        $user->load('wallet', 'tenant');
        return response()->json([
            'user' => $user,
            'wallet_balance' => $user->wallet->balance ?? 0,
        ]);
    });
});
