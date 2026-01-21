<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CheckinController;

// Admin routes
Route::group(['prefix' => 'admin'], function () {
    Route::post('/dang-nhap', [AuthController::class, 'login']);
    
    Route::middleware('auth:api')->group(function () {
        Route::post('/dang-xuat', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        // Member routes
        Route::apiResource('members', MemberController::class);

        // Checkin routes
        Route::post('/checkin', [CheckinController::class, 'checkin']);
        Route::post('/checkout', [CheckinController::class, 'checkout']);
        Route::get('/checkins/today', [CheckinController::class, 'todayCheckins']);
        Route::get('/checkins/active', [CheckinController::class, 'activeCheckins']);
    });
});

