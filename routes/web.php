<?php

use App\Http\Controllers\ActiveTypebusinessController;
use App\Http\Controllers\ActiveTypeController;
use App\Http\Controllers\AdminBlockedController;
use App\Http\Controllers\AdminCityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Auth\UserRegisterController;
use Illuminate\Support\Facades\Route;


// ── Admin Auth ──────────────────────────────────────────────────────────────
Route::get('/admin/login',            [LoginController::class,           'showlogin'])->name('admin.login');
Route::post('/admin/login',           [LoginController::class,           'login'])->name('admin.login.post');
Route::get('/admin/forgot-password',  [ForgotPasswordController::class,  'showForgotPassword'])->name('admin.forgot-password');
Route::post('/admin/forgot-password', [ForgotPasswordController::class,  'sendResetLinkEmail'])->name('admin.forgot-password.send');
Route::get('/admin/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('admin.reset-password');
Route::post('/admin/reset-password',  [ForgotPasswordController::class,  'resetPassword'])->name('admin.reset-password.update');

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
    Route::get('/ads',         [AdvertisementController::class, 'index'])->name('ads.index');
    Route::get('/ads/create',  [AdvertisementController::class, 'create'])->name('ads.create');
    Route::post('/ads',        [AdvertisementController::class, 'store'])->name('ads.store');
    Route::get('/ads/{id}/edit',[AdvertisementController::class, 'edit'])->name('ads.edit');
    Route::put('/ads/{id}',    [AdvertisementController::class, 'update'])->name('ads.update');
    Route::delete('/ads/{id}', [AdvertisementController::class, 'destroy'])->name('ads.destroy');

    // Categories
    Route::get('/categories',           [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories',          [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}',   [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Subcategories
    Route::get('/subcategories',           [SubcategoryController::class, 'index'])->name('subcategories.index');
    Route::post('/subcategories',          [SubcategoryController::class, 'store'])->name('subcategories.store');
    Route::get('/subcategories/{id}/edit', [SubcategoryController::class, 'edit'])->name('subcategories.edit');
    Route::put('/subcategories/{id}',      [SubcategoryController::class, 'update'])->name('subcategories.update');
    Route::delete('/subcategories/{id}',   [SubcategoryController::class, 'destroy'])->name('subcategories.destroy');

    // Employees
    Route::get('/employees',               [EmployeeController::class, 'allemployees'])->name('employees.index');

    // Active Types
    Route::get('/active-types',            [ActiveTypeController::class, 'index'])->name('active_types.index');
    Route::post('/active-types',           [ActiveTypeController::class, 'store'])->name('active_types.store');
    Route::delete('/active-types/{id}',    [ActiveTypeController::class, 'destroy'])->name('active_types.destroy');

    // Active Type Businesses
    Route::get('/active-type-businesses',           [ActiveTypebusinessController::class, 'index'])->name('active_typebusinesses.index');
    Route::post('/active-type-businesses',          [ActiveTypebusinessController::class, 'store'])->name('active_typebusinesses.store');
    Route::delete('/active-type-businesses/{id}',   [ActiveTypebusinessController::class, 'destroy'])->name('active_typebusinesses.destroy');

    // Cities
    Route::get('/cities',            [AdminCityController::class, 'index'])->name('cities.index');
    Route::get('/cities/create',     [AdminCityController::class, 'create'])->name('cities.create');
    Route::post('/cities',           [AdminCityController::class, 'store'])->name('cities.store');
    Route::get('/cities/{id}/edit',  [AdminCityController::class, 'edit'])->name('cities.edit');
    Route::put('/cities/{id}',       [AdminCityController::class, 'update'])->name('cities.update');
    Route::delete('/cities/{id}',    [AdminCityController::class, 'destroy'])->name('cities.destroy');

    // Workers (Business) — add show
    Route::get('/workers/{id}',      [BusinessController::class, 'show'])->name('workers.show');

    // Services Management
    Route::get('/services',          [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{id}',     [ServiceController::class, 'show'])->name('services.show');
    Route::patch('/services/{id}/toggle', [ServiceController::class, 'toggle'])->name('services.toggle');
    Route::delete('/services/{id}',  [ServiceController::class, 'destroy'])->name('services.destroy');

    // Blocked Users
    Route::get('/blocked',           [AdminBlockedController::class, 'index'])->name('blocked.index');
    Route::get('/blocked/create',    [AdminBlockedController::class, 'create'])->name('blocked.create');
    Route::post('/blocked',          [AdminBlockedController::class, 'store'])->name('blocked.store');
    Route::delete('/blocked/{id}',   [AdminBlockedController::class, 'destroy'])->name('blocked.destroy');

    // Categories — add edit/update
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}',      [CategoryController::class, 'update'])->name('categories.update');
});

// ── Super Admin Auth ────────────────────────────────────────────────────────
Route::get('/super-admin/login',            [SuperAdminLoginController::class, 'showLogin'])->name('super_admin.login');
Route::post('/super-admin/login',           [SuperAdminLoginController::class, 'login'])->name('super_admin.login.post');
Route::get('/super-admin/forgot-password',  fn() => view('auth.super_admin.forgot-password'))->name('super_admin.forgot-password');
Route::post('/super-admin/forgot-password', fn() => back()->with('status', 'تم إرسال رابط الاستعادة.'))->name('super_admin.forgot-password.send');

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
Route::get('/login',                    [UserLoginController::class,    'showLogin'])->name('user.login');
Route::post('/login',                   [UserLoginController::class,    'login'])->name('user.login.post');
Route::get('/register',                 [UserRegisterController::class, 'showRegister'])->name('user.register');
Route::post('/register',                [UserRegisterController::class, 'register'])->name('user.register.post');
Route::get('/forgot-password',          fn() => view('auth.user.forgot-password'))->name('user.forgot-password');
Route::post('/forgot-password',         fn() => back()->with('status', 'تم إرسال رابط الاستعادة.'))->name('user.forgot-password.send');
Route::get('/reset-password/{token}',   fn($token) => view('auth.user.reset-password', compact('token')))->name('user.reset-password');
Route::post('/reset-password',          fn() => redirect()->route('user.login')->with('status', 'تم تغيير كلمة المرور.'))->name('user.reset-password.update');

// ── User Panel ───────────────────────────────────────────────────────────────
Route::prefix('user')->name('user.')->middleware('auth_user')->group(function () {
    Route::post('/logout',   [UserLoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard',  [UserDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile',    [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile',    [UserDashboardController::class, 'updateProfile'])->name('profile.update');

    // Business
    Route::post('/business',          [UserDashboardController::class, 'storeBusiness'])->name('business.store');
    Route::put('/business',           [UserDashboardController::class, 'updateBusiness'])->name('business.update');

    // My Services
    Route::post('/my-services',           [UserDashboardController::class, 'storeService'])->name('my-services.store');
    Route::put('/my-services/{id}',       [UserDashboardController::class, 'updateService'])->name('my-services.update');
    Route::delete('/my-services/{id}',    [UserDashboardController::class, 'destroyService'])->name('my-services.destroy');

    // Explore & Services browse
    Route::get('/explore',              [UserDashboardController::class, 'explore'])->name('explore');
    Route::get('/services',             [ServiceController::class, 'servicesusers'])->name('services');
    Route::get('/services/{id}/details',[ServiceController::class, 'serviceDetails'])->name('services.details');
    Route::post('/chat/start',          [UserDashboardController::class, 'startChat'])->name('chat.start');

    // Posts
    Route::get('/posts', [PostController::class, 'showmypost'])->name('posts');

    // Conversations list
    Route::get('/conversations', [UserDashboardController::class, 'conversations'])->name('conversations');

    // Chat
    Route::get('/chat/{conversationId}', [ChatController::class, 'show'])->name('chat');

    // Messages (AJAX)
    Route::get('/messages/unread-count',              [MessageController::class, 'unreadCount'])->name('messages.unread');
    Route::post('/messages/{conversationId}/mark-read',[MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages',                          [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversationId}',          [MessageController::class, 'index'])->name('messages.index');
});
// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});
