<?php

use Illuminate\Support\Facades\Route;
use App\ClubOpsEdition;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\OnboardingController;

Route::middleware('guest')->group(function () {
    // Direct registration (no mode selector) — primary "Create Your Club" entry
    Route::get('register', [OnboardingController::class, 'register'])->name('register');
    Route::post('register', [OnboardingController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('register.store');

    // Setup with mode selector — power-user path
    Route::get('setup', [OnboardingController::class, 'setup'])->name('setup');
    Route::post('setup', [OnboardingController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('setup.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1');

    // Password Reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    Route::put('password', [PasswordController::class, 'update'])->name('password.change');
});
