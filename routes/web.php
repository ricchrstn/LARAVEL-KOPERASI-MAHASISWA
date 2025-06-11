<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\FirebaseLoginController;
use App\Http\Controllers\Auth\AuthController;

Route::prefix('kopma')->group(function () {
    // Auth routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');

    // Dashboard, simpanan, pinjaman, dll
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin']);

    Route::post('/verify-firebase-token', [FirebaseLoginController::class, 'verify']);

    Route::get('/simpanan', function () {
        return view('simpanan');
    });

    Route::get('/profil', function () {
        return view('profil');
    });

    Route::get('/pinjaman', function () {
        return view('pinjaman');
    });

    Route::get('/feedback', function () {
        return view('feedback');
    });

    Route::get('/about', function () {
        return view('about');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
