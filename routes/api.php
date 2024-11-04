<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Auth\Events\PasswordResetLinkSent;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Contracts\PasswordResetResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('auth')->group(function () {
        Route::get('/email/verification-notification', [EmailVerificationNotificationController::class, 'store']);
        Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'store'])
            ->middleware('signed')
            ->name('verification.verify')
            ->withoutMiddleware('auth:sanctum');
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email')
            ->withoutMiddleware('auth:sanctum');
        Route::get('/reset-password', [ResetPasswordController::class, 'getToken'])
            ->name('password.reset')
            ->withoutMiddleware('auth:sanctum');
        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->name('password.reset')
            ->withoutMiddleware('auth:sanctum');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter(['throttle:login']))->withoutMiddleware('auth:sanctum');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->withoutMiddleware('auth:sanctum');
    });

    Route::get('/oio', [UserController::class, 'index']);
});
