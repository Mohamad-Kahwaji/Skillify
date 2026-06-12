<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth_SuperAdmin\LoginController as SuperAdminLoginController;
use App\Http\Controllers\Auth_User\LoginController    as UserLoginController;
use App\Http\Controllers\Auth_User\RegisterController  as UserRegisterController;
use Illuminate\Support\Facades\Route;

// ════════════════════════════════════════════════════════════
// ADMIN AUTH
// ════════════════════════════════════════════════════════════
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login',                        [LoginController::class,          'showlogin'])->name('login');
    Route::post('/login',                       [LoginController::class,          'login'])->name('login.post');

    Route::get('/forgot-password',              [ForgotPasswordController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password',             [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.send');

    Route::get('/reset-password/{token}',       [ForgotPasswordController::class, 'showResetForm'])->name('reset-password');
    Route::post('/reset-password',              [ForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});

// ════════════════════════════════════════════════════════════
// SUPER ADMIN AUTH
// ════════════════════════════════════════════════════════════
Route::prefix('super-admin')->name('super_admin.')->group(function () {

    Route::get('/login',                        [SuperAdminLoginController::class, 'showLogin'])->name('login');
    Route::post('/login',                       [SuperAdminLoginController::class, 'login'])->name('login.post');

    Route::get('/forgot-password',              fn() => view('auth.super_admin.forgot-password'))->name('forgot-password');
    Route::post('/forgot-password',             fn() => back()->with('status', 'If an account with that email exists, a reset link has been sent.'))->name('forgot-password.send');

    Route::get('/reset-password/{token}',       fn($token) => view('auth.super_admin.reset-password', compact('token')))->name('reset-password');
    Route::post('/reset-password',              fn() => redirect()->route('super_admin.login')->with('status', 'Your password has been reset successfully.'))->name('reset-password.update');
});

// ════════════════════════════════════════════════════════════
// USER AUTH
// ════════════════════════════════════════════════════════════
Route::name('user.')->group(function () {

    Route::get('/login',                        [UserLoginController::class,   'showLogin'])->name('login');
    Route::post('/login',                       [UserLoginController::class,   'login'])->name('login.post');

    Route::get('/register',                     [UserRegisterController::class,'showRegister'])->name('register');
    Route::post('/register',                    [UserRegisterController::class,'register'])->name('register.post');

    Route::get('/forgot-password',              fn() => view('auth.user.forgot-password'))->name('forgot-password');
    Route::post('/forgot-password',             fn() => back()->with('status', 'If an account with that email exists, a reset link has been sent.'))->name('forgot-password.send');

    Route::get('/reset-password/{token}',       fn($token) => view('auth.user.reset-password', compact('token')))->name('reset-password');
    Route::post('/reset-password',              fn() => redirect()->route('user.login')->with('status', 'Your password has been reset successfully. Please sign in.'))->name('reset-password.update');
});
