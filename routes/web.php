<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfilController;

// Route global (jika masih digunakan)
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Prefix route untuk kopma
Route::prefix('kopma')->group(function () {
    // Auth routes
    Route::get('login', [FirebaseAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [FirebaseAuthController::class, 'login']);
    Route::post('logout', [FirebaseAuthController::class, 'logout'])->name('logout');

    // Registration and password reset (opsional jika digunakan Firebase sepenuhnya)
    Route::get('register', [FirebaseAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [FirebaseAuthController::class, 'register']);
    Route::get('forgot-password', [FirebaseAuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [FirebaseAuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [FirebaseAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [FirebaseAuthController::class, 'reset'])->name('password.update');

    // Route untuk verifikasi Firebase token
    Route::post('/verify-firebase-token', [FirebaseAuthController::class, 'verify']);

    // Protected routes
    Route::middleware(['auth'])->group(function () {
        // User routes
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('simpanan', [SimpananController::class, 'index'])->name('simpanan');
        Route::get('pinjaman', [PinjamanController::class, 'index'])->name('pinjaman');
        Route::get('profil', [ProfilController::class, 'index'])->name('profil');

        // Optional static pages
        Route::view('feedback', 'feedback')->name('feedback');
        Route::view('about', 'about')->name('about');

        // Admin routes
        Route::middleware(['role:admin'])->group(function () {
            Route::get('admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        });
    });
});
