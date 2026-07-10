<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth_SuperAdmin\ForgotPasswordController as SuperAdminForgotPasswordController;
use App\Http\Controllers\Auth_SuperAdmin\LoginController as SuperAdminLoginController;
use App\Http\Controllers\Auth_User\ForgotPasswordController as UserForgotPasswordController;
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
    Route::post('/forgot-password',             [ForgotPasswordController::class, 'sendOtp'])
                                                ->middleware('throttle:3,1')->name('forgot-password.send');

    Route::get('/verify-otp',                   [ForgotPasswordController::class, 'showVerifyOtp'])->name('verify-otp');
    Route::post('/verify-otp',                  [ForgotPasswordController::class, 'verifyOtp'])
                                                ->middleware('throttle:10,1')->name('verify-otp.post');

    Route::get('/reset-password',               [ForgotPasswordController::class, 'showResetPassword'])->name('reset-password');
    Route::post('/reset-password',              [ForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});

// ════════════════════════════════════════════════════════════
// SUPER ADMIN AUTH
// ════════════════════════════════════════════════════════════
Route::prefix('super-admin')->name('super_admin.')->group(function () {

    Route::get('/login',                        [SuperAdminLoginController::class, 'showLogin'])->name('login');
    Route::post('/login',                       [SuperAdminLoginController::class, 'login'])->name('login.post');

    Route::get('/forgot-password',              [SuperAdminForgotPasswordController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password',             [SuperAdminForgotPasswordController::class, 'sendOtp'])
                                                ->middleware('throttle:3,1')->name('forgot-password.send');

    Route::get('/verify-otp',                   [SuperAdminForgotPasswordController::class, 'showVerifyOtp'])->name('verify-otp');
    Route::post('/verify-otp',                  [SuperAdminForgotPasswordController::class, 'verifyOtp'])
                                                ->middleware('throttle:10,1')->name('verify-otp.post');

    Route::get('/reset-password',               [SuperAdminForgotPasswordController::class, 'showResetPassword'])->name('reset-password');
    Route::post('/reset-password',              [SuperAdminForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});

// ════════════════════════════════════════════════════════════
// USER AUTH
// ════════════════════════════════════════════════════════════
Route::name('user.')->group(function () {

    Route::get('/login',                        [UserLoginController::class,   'showLogin'])->name('login');
    Route::post('/login',                       [UserLoginController::class,   'login'])->name('login.post');

    Route::get('/register',                     [UserRegisterController::class,'showRegister'])->name('register');
    Route::post('/register',                    [UserRegisterController::class,'register'])->name('register.post');

    Route::get('/forgot-password',              [UserForgotPasswordController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password',             [UserForgotPasswordController::class, 'sendOtp'])
                                                ->middleware('throttle:3,1')->name('forgot-password.send');

    Route::get('/verify-otp',                   [UserForgotPasswordController::class, 'showVerifyOtp'])->name('verify-otp');
    Route::post('/verify-otp',                  [UserForgotPasswordController::class, 'verifyOtp'])
                                                ->middleware('throttle:10,1')->name('verify-otp.post');

    Route::get('/reset-password',               [UserForgotPasswordController::class, 'showResetForm'])->name('reset-password');
    Route::post('/reset-password',              [UserForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});
