<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Auth\UserRegisterController;
use Illuminate\Support\Facades\Route;

// ── Admin Auth ──────────────────────────────────────────────────────────────
Route::get('/admin/login', [LoginController::class, 'showlogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');

// ── Admin Panel ─────────────────────────────────────────────────────────────
// أضف ->middleware('auth_admin') لما تخلص التطوير
Route::prefix('admin')->name('admin.')->group(function () {

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'allusers'])->name('users.index');
    Route::patch('/users/{user}/activate',   [UserController::class, 'active'])->name('users.activate');
    Route::patch('/users/{user}/deactivate', [UserController::class, 'inactive'])->name('users.deactivate');
    Route::delete('/users/{user}', [AdminController::class, 'deleteaccountsuser'])->name('users.destroy');

    // Workers (Businesses)
    Route::get('/workers', [BusinessController::class, 'index'])->name('workers.index');
    Route::patch('/workers/{id}/approve', [AdminController::class, 'approvebusiness'])->name('workers.approve');
    Route::patch('/workers/{id}/reject',  [AdminController::class, 'rejectbusiness'])->name('workers.reject');
    Route::delete('/workers/{id}', [BusinessController::class, 'destroy'])->name('workers.destroy');

    // Verifications (pending businesses)
    Route::get('/verifications', [AdminController::class, 'verifications'])->name('verifications.index');
    Route::patch('/verifications/{id}/approve', [AdminController::class, 'approvebusiness'])->name('verifications.approve');
    Route::patch('/verifications/{id}/reject',  [AdminController::class, 'rejectbusiness'])->name('verifications.reject');

    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/post/{id}', [ReportController::class, 'reportpost'])->name('reports.post');

    // Advertisements
    Route::get('/ads', [AdvertisementController::class, 'index'])->name('ads.index');
    Route::get('/ads/create', [AdvertisementController::class, 'create'])->name('ads.create');
    Route::post('/ads', [AdvertisementController::class, 'store'])->name('ads.store');
    Route::get('/ads/{id}/edit', [AdvertisementController::class, 'edit'])->name('ads.edit');
    Route::put('/ads/{id}', [AdvertisementController::class, 'update'])->name('ads.update');
    Route::delete('/ads/{id}', [AdvertisementController::class, 'destroy'])->name('ads.destroy');
});

// ── Super Admin Auth ────────────────────────────────────────────────────────
Route::get('/super-admin/login',  [SuperAdminLoginController::class, 'showLogin'])->name('super_admin.login');
Route::post('/super-admin/login', [SuperAdminLoginController::class, 'login'])->name('super_admin.login.post');

// ── Super Admin Panel ────────────────────────────────────────────────────────
Route::prefix('super-admin')->name('super_admin.')->middleware('auth_super_admin')->group(function () {
    Route::post('/logout', [SuperAdminLoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/admins',         [SuperAdminController::class, 'admins'])->name('admins.index');
    Route::get('/admins/create',  [SuperAdminController::class, 'createAdmin'])->name('admins.create');
    Route::post('/admins',        [SuperAdminController::class, 'storeAdmin'])->name('admins.store');
    Route::delete('/admins/{admin}', [SuperAdminController::class, 'deleteAdmin'])->name('admins.destroy');
});

// ── User Auth ────────────────────────────────────────────────────────────────
Route::get('/login',    [UserLoginController::class, 'showLogin'])->name('user.login');
Route::post('/login',   [UserLoginController::class, 'login'])->name('user.login.post');
Route::get('/register', [UserRegisterController::class, 'showRegister'])->name('user.register');
Route::post('/register',[UserRegisterController::class, 'register'])->name('user.register.post');

// ── User Panel ───────────────────────────────────────────────────────────────
Route::prefix('user')->name('user.')->middleware('auth_user')->group(function () {
    Route::post('/logout',    [UserLoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard',  [UserDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile',    [UserDashboardController::class, 'profile'])->name('profile');
});

// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});
