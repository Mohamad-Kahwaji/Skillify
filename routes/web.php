<?php
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ActiveTypebusinessController;
use App\Http\Controllers\ActiveTypeController;
use App\Http\Controllers\AdminBlockedController;
use App\Http\Controllers\AdminCityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BusinessGalleryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\IdentityVerificationController;
use App\Http\Controllers\Auth_SuperAdmin\LoginController as SuperAdminLoginController;
use App\Http\Controllers\Auth_User\LogoutController as UserLogoutController;
use App\Http\Controllers\FcmTokenController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// ── Auth routes (login, register, forgot/reset password) ────────────────────
require __DIR__.'/auth.php';

// Broadcasting auth is registered in AppServiceProvider to take priority over the framework route.

// ════════════════════════════════════════════════════════════════════════════
// ADMIN PANEL
// ════════════════════════════════════════════════════════════════════════════
Route::prefix('admin')->name('admin.')->middleware('auth_admin')->group(function () {

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update')
        ->middleware('confirm_admin_password');

    // Users
    Route::get('/users',                    [UserController::class,  'allusers'])->name('users.index')
        ->middleware('permission:users.view');
    Route::patch('/users/{user}/activate',  [UserController::class,  'active'])->name('users.activate')
        ->middleware('permission:users.activate');
    Route::patch('/users/{user}/deactivate',[UserController::class,  'inactive'])->name('users.deactivate')
        ->middleware('permission:users.deactivate');
    Route::delete('/users/{user}',          [AdminController::class, 'deleteaccountsuser'])->name('users.destroy')
        ->middleware('permission:users.delete');
    Route::get('/users/no-services',        [UserController::class,  'services_users'])->name('users.no-services')
        ->middleware('permission:users.view_no_services');
    Route::get('/users/{user}/profile',     [UserController::class,  'adminProfile'])->name('users.profile')
        ->middleware('permission:users.view');

    // Workers (Businesses)
    Route::get('/workers',               [BusinessController::class, 'index'])->name('workers.index')
        ->middleware('permission:businesses.view');
    Route::get('/workers/{id}',          [BusinessController::class, 'show'])->name('workers.show')
        ->middleware('permission:businesses.show');
    Route::patch('/workers/{business}/approve',[AdminController::class, 'businessto_approve'])->name('workers.approve')
        ->middleware('permission:businesses.approve');
    Route::patch('/workers/{business}/reject', [AdminController::class, 'businessto_rejected'])->name('workers.reject')
        ->middleware('permission:businesses.reject');
    Route::patch('/workers/{business}/pending',[AdminController::class, 'businessto_pending'])->name('workers.pending');
    Route::delete('/workers/{id}',       [BusinessController::class, 'destroy'])->name('workers.destroy')
        ->middleware('permission:businesses.delete');

    // Requests pages
    Route::get('/business-requests', [AdminController::class, 'businessRequests'])->name('business-requests.index');
    Route::get('/service-requests',  [AdminController::class, 'serviceRequests'])->name('service-requests.index');
    Route::patch('/service-requests/{service}/approve', [AdminController::class, 'serviceto_approve'])->name('service-requests.approve');
    Route::patch('/service-requests/{service}/reject',  [AdminController::class, 'serviceto_rejected'])->name('service-requests.reject');
    Route::patch('/service-requests/{service}/pending', [AdminController::class, 'serviceto_pending'])->name('service-requests.pending');

    // Business Verifications
    Route::get('/verifications',                 [AdminController::class, 'verifications'])->name('verifications.index')
        ->middleware('permission:verifications.view');
    Route::patch('/verifications/{business}/approve',  [AdminController::class, 'businessto_approve'])->name('verifications.approve')
        ->middleware('permission:verifications.approve');
    Route::patch('/verifications/{business}/reject',   [AdminController::class, 'businessto_rejected'])->name('verifications.reject')
        ->middleware('permission:verifications.reject');

    // Identity Verifications
    Route::get('/identity-verifications',                            [IdentityVerificationController::class, 'adminIndex'])->name('identity.index')
        ->middleware('permission:verifications.view');
    Route::post('/identity-verifications/analyse-all',               [IdentityVerificationController::class, 'analyseAll'])->name('identity.analyse-all');
    Route::patch('/identity-verifications/{verification}/approve',   [IdentityVerificationController::class, 'approve'])->name('identity.approve')
        ->middleware('permission:verifications.approve');
    Route::patch('/identity-verifications/{verification}/reject',    [IdentityVerificationController::class, 'reject'])->name('identity.reject')
        ->middleware('permission:verifications.reject');
    Route::patch('/identity-verifications/{verification}/pending',   [IdentityVerificationController::class, 'resetToPending'])->name('identity.pending');
    Route::post('/identity-verifications/{verification}/analyse-ai', [IdentityVerificationController::class, 'analyseWithAi'])->name('identity.analyse');

    // Posts
    Route::get('/posts',         [PostController::class, 'index'])->name('posts.index')
        ->middleware('permission:posts.view_all');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy')
        ->middleware('permission:posts.delete');
    Route::delete('/comments/{comment}', [CommentController::class, 'adminDestroy'])->name('comments.destroy')
        ->middleware('permission:posts.delete');

    // Reports
    Route::get('/reports',           [ReportController::class, 'index'])->name('reports.index')
        ->middleware('permission:reports.view');
    Route::get('/reports/post/{id}', [ReportController::class, 'reportpost'])->name('reports.post')
        ->middleware('permission:reports.view');

    // Advertisements
    Route::get('/ads',           [AdvertisementController::class, 'index'])->name('ads.index')
        ->middleware('permission:ads.view');
    Route::get('/ads/create',    [AdvertisementController::class, 'create'])->name('ads.create')
        ->middleware('permission:ads.create');
    Route::post('/ads',          [AdvertisementController::class, 'store'])->name('ads.store')
        ->middleware('permission:ads.create');
    Route::get('/ads/{id}/edit', [AdvertisementController::class, 'edit'])->name('ads.edit')
        ->middleware('permission:ads.edit');
    Route::put('/ads/{id}',      [AdvertisementController::class, 'update'])->name('ads.update')
        ->middleware('permission:ads.edit');
    Route::delete('/ads/{id}',   [AdvertisementController::class, 'destroy'])->name('ads.delete')
        ->middleware('permission:ads.delete');

    // Categories
    Route::get('/categories',            [CategoryController::class, 'index'])->name('categories.index')
        ->middleware('permission:categories.view');
    Route::post('/categories',           [CategoryController::class, 'store'])->name('categories.store')
        ->middleware('permission:categories.create');
    Route::get('/categories/{id}/edit',  [CategoryController::class, 'edit'])->name('categories.edit')
        ->middleware('permission:categories.edit');
    Route::put('/categories/{id}',       [CategoryController::class, 'update'])->name('categories.update')
        ->middleware('permission:categories.edit');
    Route::delete('/categories/{id}',    [CategoryController::class, 'destroy'])->name('categories.destroy')
        ->middleware('permission:categories.delete');

    // Subcategories
    Route::get('/subcategories',           [SubcategoryController::class, 'index'])->name('subcategories.index')
        ->middleware('permission:subcategories.view');
    Route::post('/subcategories',          [SubcategoryController::class, 'store'])->name('subcategories.store')
        ->middleware('permission:subcategories.create');
    Route::get('/subcategories/{id}/edit', [SubcategoryController::class, 'edit'])->name('subcategories.edit')
        ->middleware('permission:subcategories.edit');
    Route::put('/subcategories/{id}',      [SubcategoryController::class, 'update'])->name('subcategories.update')
        ->middleware('permission:subcategories.edit');
    Route::delete('/subcategories/{id}',   [SubcategoryController::class, 'destroy'])->name('subcategories.destroy')
        ->middleware('permission:subcategories.delete');

    // Active Types
    Route::get('/active-types',          [ActiveTypeController::class, 'index'])->name('active_types.index')
        ->middleware('permission:active_types.view');
    Route::post('/active-types',         [ActiveTypeController::class, 'store'])->name('active_types.store')
        ->middleware('permission:active_types.create');
    Route::patch('/active-types/{id}',   [ActiveTypeController::class, 'update'])->name('active_types.update')
        ->middleware('permission:active_types.create');
    Route::delete('/active-types/{id}',  [ActiveTypeController::class, 'destroy'])->name('active_types.destroy')
        ->middleware('permission:active_types.delete');

    // Active Type Businesses
    Route::get('/active-type-businesses',          [ActiveTypebusinessController::class, 'index'])->name('active_typebusinesses.index')
        ->middleware('permission:active_type_businesses.view');
    Route::post('/active-type-businesses',         [ActiveTypebusinessController::class, 'store'])->name('active_typebusinesses.store')
        ->middleware('permission:active_type_businesses.create');
    Route::patch('/active-type-businesses/{id}',   [ActiveTypebusinessController::class, 'update'])->name('active_typebusinesses.update')
        ->middleware('permission:active_type_businesses.create');
    Route::delete('/active-type-businesses/{id}',  [ActiveTypebusinessController::class, 'destroy'])->name('active_typebusinesses.destroy')
        ->middleware('permission:active_type_businesses.delete');

    // Cities
    Route::get('/cities',            [AdminCityController::class, 'index'])->name('cities.index')
        ->middleware('permission:cities.view');
    Route::get('/cities/create',     [AdminCityController::class, 'create'])->name('cities.create')
        ->middleware('permission:cities.create');
    Route::post('/cities',           [AdminCityController::class, 'store'])->name('cities.store')
        ->middleware('permission:cities.create');
    Route::get('/cities/{id}/edit',  [AdminCityController::class, 'edit'])->name('cities.edit')
        ->middleware('permission:cities.edit');
    Route::put('/cities/{id}',       [AdminCityController::class, 'update'])->name('cities.update')
        ->middleware('permission:cities.edit');
    Route::delete('/cities/{id}',    [AdminCityController::class, 'destroy'])->name('cities.destroy')
        ->middleware('permission:cities.delete');

    // Services
    Route::get('/services',                [ServiceController::class, 'index'])->name('services.index')
        ->middleware('permission:services.view');
    Route::get('/services/{id}',           [ServiceController::class, 'show'])->name('services.show')
        ->middleware('permission:services.view');
    Route::patch('/services/{id}/toggle',  [ServiceController::class, 'toggle'])->name('services.toggle')
        ->middleware('permission:services.toggle');
    Route::delete('/services/{id}',        [ServiceController::class, 'destroy'])->name('services.destroy')
        ->middleware('permission:services.delete');

    // Blocked Users
    Route::get('/blocked',           [AdminBlockedController::class, 'index'])->name('blocked.index')
        ->middleware('permission:blocked.view');
    Route::get('/blocked/create',    [AdminBlockedController::class, 'create'])->name('blocked.create')
        ->middleware('permission:blocked.create');
    Route::post('/blocked',          [AdminBlockedController::class, 'store'])->name('blocked.store')
        ->middleware('permission:blocked.create');
    Route::delete('/blocked/{id}',   [AdminBlockedController::class, 'destroy'])->name('blocked.destroy')
        ->middleware('permission:blocked.delete');

    // Notifications
    Route::get('/notifications',              [AdminController::class, 'notifications'])->name('notifications.index');
    Route::patch('/notifications/read-all',   [AdminController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    Route::patch('/notifications/{id}/read',  [AdminController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/notify-user', [NotificationController::class, 'notifyUser'])->name('notifications.notify-user');
});

// ════════════════════════════════════════════════════════════════════════════
// SUPER ADMIN PANEL
// ════════════════════════════════════════════════════════════════════════════
Route::prefix('super-admin')->name('super_admin.')->middleware('auth_super_admin')->group(function () {

    Route::post('/logout', [SuperAdminLoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // My Profile
    Route::get('/profile', [SuperAdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [SuperAdminController::class, 'updateProfile'])->name('profile.update')
        ->middleware('confirm_admin_password:super_admins');

    // Admins management — no permission middleware: super admin has unrestricted access
    Route::get('/admins',                      [SuperAdminController::class, 'admins'])->name('admins.index');
    Route::get('/admins/create',               [SuperAdminController::class, 'createAdmin'])->name('admins.create');
    Route::post('/admins',                     [SuperAdminController::class, 'storeAdmin'])->name('admins.store');
    Route::delete('/admins/{admin}',              [SuperAdminController::class, 'deleteAdmin'])->name('admins.destroy');
    Route::patch('/admins/{admin}/activate',      [AdminController::class, 'admin_active'])->name('admins.activate');
    Route::patch('/admins/{admin}/deactivate',    [AdminController::class, 'admin_inactive'])->name('admins.deactivate');
    Route::patch('/admins/{admin}/assign-role',   [SuperAdminController::class, 'assignAdminRole'])->name('admins.assign-role');
    Route::patch('/admins/{admin}/revoke-roles',  [SuperAdminController::class, 'revokeAdminRole'])->name('admins.revoke-roles');

    // Permissions
    Route::get('/permissions',                 [RolePermissionController::class, 'permissions'])->name('permissions.index');
    Route::post('/permissions',                [RolePermissionController::class, 'storePermission'])->name('permissions.store');
    Route::delete('/permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])->name('permissions.destroy');

    // Roles
    Route::get('/roles',           [RolePermissionController::class, 'roles'])->name('roles.index');
    Route::post('/roles',          [RolePermissionController::class, 'storeRole'])->name('roles.store');
    Route::put('/roles/{role}',    [RolePermissionController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{role}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');

    // Notifications
    Route::get('/notifications',               [SuperAdminController::class, 'notifications'])->name('notifications.index');
    Route::patch('/notifications/{id}/read',   [SuperAdminController::class, 'markNotificationRead'])->name('notifications.read');
    Route::patch('/notifications/read-all',    [SuperAdminController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    Route::post('/notifications/notify-admin', [NotificationController::class, 'notifyAdmin'])->name('notifications.notify-admin');
    Route::post('/notifications/announce',     [NotificationController::class, 'announceToAll'])->name('notifications.announce');

    // Users
    Route::get('/users',              [SuperAdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/profile', [UserController::class,     'superAdminProfile'])->name('users.profile');
    Route::delete('/users/{user}',    [SuperAdminController::class, 'destroyUser'])->name('users.destroy');

    // Businesses
    Route::get('/businesses',                         [SuperAdminController::class, 'businesses'])->name('businesses.index');
    Route::patch('/businesses/{business}/approve',    [SuperAdminController::class, 'businessto_approve'])->name('businesses.approve');
    Route::patch('/businesses/{business}/reject',     [SuperAdminController::class, 'businessto_rejected'])->name('businesses.reject');
    Route::patch('/businesses/{business}/pending',    [SuperAdminController::class, 'businessto_pending'])->name('businesses.pending');
    Route::delete('/businesses/{business}',           [SuperAdminController::class, 'destroyBusiness'])->name('businesses.destroy');

    // Services
    Route::get('/services',                        [SuperAdminController::class, 'services'])->name('services.index');
    Route::patch('/services/{service}/approve',    [SuperAdminController::class, 'serviceto_approve'])->name('services.approve');
    Route::patch('/services/{service}/reject',     [SuperAdminController::class, 'serviceto_rejected'])->name('services.reject');
    Route::patch('/services/{service}/pending',    [SuperAdminController::class, 'serviceto_pending'])->name('services.pending');
    Route::delete('/services/{service}',           [SuperAdminController::class, 'destroyService'])->name('services.destroy');

    // Requests pages
    Route::get('/business-requests',                          [SuperAdminController::class, 'businessRequests'])->name('business-requests.index');
    Route::get('/service-requests',                           [SuperAdminController::class, 'serviceRequests'])->name('service-requests.index');
    Route::patch('/service-requests/{service}/approve',       [SuperAdminController::class, 'serviceto_approve'])->name('service-requests.approve');
    Route::patch('/service-requests/{service}/reject',        [SuperAdminController::class, 'serviceto_rejected'])->name('service-requests.reject');
    Route::patch('/service-requests/{service}/pending',       [SuperAdminController::class, 'serviceto_pending'])->name('service-requests.pending');
    Route::patch('/business-requests/{business}/approve',     [SuperAdminController::class, 'businessto_approve'])->name('business-requests.approve');
    Route::patch('/business-requests/{business}/reject',      [SuperAdminController::class, 'businessto_rejected'])->name('business-requests.reject');
    Route::patch('/business-requests/{business}/pending',     [SuperAdminController::class, 'businessto_pending'])->name('business-requests.pending');

    // Ads
    Route::get('/ads',               [SuperAdminController::class, 'ads'])->name('ads.index');
    Route::patch('/ads/{ad}/toggle', [SuperAdminController::class, 'toggleAd'])->name('ads.toggle');
    Route::delete('/ads/{ad}',       [SuperAdminController::class, 'destroyAd'])->name('ads.destroy');

    // Posts
    Route::get('/posts',             [SuperAdminController::class, 'posts'])->name('posts.index');
    Route::delete('/posts/{post}',   [SuperAdminController::class, 'destroyPost'])->name('posts.destroy');
    Route::delete('/comments/{comment}', [CommentController::class, 'adminDestroy'])->name('comments.destroy');

    // Identity Verifications
    Route::get('/identity-verifications',                              [SuperAdminController::class, 'identityVerifications'])->name('identity.index');
    Route::post('/identity-verifications/analyse-all',                 [SuperAdminController::class, 'analyseAllIdentities'])->name('identity.analyse-all');
    Route::patch('/identity-verifications/{verification}/approve',     [SuperAdminController::class, 'approveIdentity'])->name('identity.approve');
    Route::patch('/identity-verifications/{verification}/reject',      [SuperAdminController::class, 'rejectIdentity'])->name('identity.reject');
    Route::patch('/identity-verifications/{verification}/pending',     [SuperAdminController::class, 'pendingIdentity'])->name('identity.pending');
    Route::post('/identity-verifications/{verification}/analyse-ai',   [SuperAdminController::class, 'analyseIdentityWithAi'])->name('identity.analyse');

    // Reports
    Route::get('/reports', [SuperAdminController::class, 'reports'])->name('reports.index');

    // Blocked
    Route::get('/blocked',          [SuperAdminController::class,  'blocked'])->name('blocked.index');
    Route::post('/blocked',         [AdminBlockedController::class, 'store'])->name('blocked.store');
    Route::delete('/blocked/{id}',  [AdminBlockedController::class, 'destroy'])->name('blocked.destroy');

    // Categories
    Route::get('/categories',           [SuperAdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories',          [CategoryController::class,   'store'])->name('categories.store');
    Route::put('/categories/{id}',      [CategoryController::class,   'update'])->name('categories.update');
    Route::delete('/categories/{id}',   [CategoryController::class,   'destroy'])->name('categories.destroy');

    // Subcategories
    Route::get('/subcategories',        [SuperAdminController::class,  'subcategories'])->name('subcategories.index');
    Route::post('/subcategories',       [SubcategoryController::class, 'store'])->name('subcategories.store');
    Route::put('/subcategories/{id}',   [SubcategoryController::class, 'update'])->name('subcategories.update');
    Route::delete('/subcategories/{id}',[SubcategoryController::class, 'destroy'])->name('subcategories.destroy');

    // Active Types
    Route::get('/active-types',         [SuperAdminController::class, 'activeTypes'])->name('active_types.index');
    Route::post('/active-types',        [ActiveTypeController::class, 'store'])->name('active_types.store');
    Route::patch('/active-types/{id}',  [ActiveTypeController::class, 'update'])->name('active_types.update');
    Route::delete('/active-types/{id}', [ActiveTypeController::class, 'destroy'])->name('active_types.destroy');

    // Active Type Businesses
    Route::get('/active-type-businesses',         [SuperAdminController::class,       'activeTypeBusinesses'])->name('active_typebusinesses.index');
    Route::post('/active-type-businesses',        [ActiveTypebusinessController::class,'store'])->name('active_typebusinesses.store');
    Route::patch('/active-type-businesses/{id}',  [ActiveTypebusinessController::class,'update'])->name('active_typebusinesses.update');
    Route::delete('/active-type-businesses/{id}', [ActiveTypebusinessController::class,'destroy'])->name('active_typebusinesses.destroy');

    // Cities
    Route::get('/cities',           [SuperAdminController::class, 'cities'])->name('cities.index');
    Route::post('/cities',          [AdminCityController::class,  'store'])->name('cities.store');
    Route::put('/cities/{id}',      [AdminCityController::class,  'update'])->name('cities.update');
    Route::delete('/cities/{id}',   [AdminCityController::class,  'destroy'])->name('cities.destroy');
});

// ════════════════════════════════════════════════════════════════════════════
// USER PANEL
// ════════════════════════════════════════════════════════════════════════════
Route::prefix('user')->name('user.')->middleware('auth_user')->group(function () {

    Route::post('/logout',   [UserLogoutController::class,    'logout'])->name('logout');
    Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');

    // Business
    Route::post('/business', [BusinessController::class, 'store'])->name('business.store')
        ->middleware('permission:business.create');
    Route::put('/business',    [BusinessController::class, 'edit'])->name('business.update')
        ->middleware('permission:business.update');
    Route::patch('/business/resubmit', [BusinessController::class, 'resubmit'])->name('business.resubmit')
        ->middleware('permission:business.update');

    // Business Gallery
    Route::post('/business/gallery',                           [BusinessGalleryController::class, 'store'])->name('business.gallery.store');
    Route::delete('/business/gallery/{gallery}',               [BusinessGalleryController::class, 'destroy'])->name('business.gallery.destroy');
    Route::patch('/business/gallery/{gallery}/caption',        [BusinessGalleryController::class, 'updateCaption'])->name('business.gallery.caption');

    // My Services (read)
    Route::get('/my-services', [UserController::class, 'myservices'])->name('my-services');

    // My Services (write) — requires active business account
    Route::post('/my-services',        [ServiceController::class, 'createService'])->name('my-services.store')
        ->middleware(['has_business', 'permission:my_services.create']);
    Route::put('/my-services/{id}',    [UserDashboardController::class, 'updateService'])->name('my-services.update')
        ->middleware(['has_business', 'permission:my_services.edit']);
    Route::delete('/my-services/{id}', [UserDashboardController::class, 'destroyService'])->name('my-services.destroy')
        ->middleware('permission:my_services.delete');

    // Explore & Browse
    Route::get('/explore',               [UserDashboardController::class, 'explore'])->name('explore');
    Route::get('/services',              [ServiceController::class,       'servicesusers'])->name('services');
    Route::get('/services/{id}/details', [ServiceController::class,       'serviceDetails'])->name('services.details');
    Route::post('/chat/start',           [UserDashboardController::class, 'startChat'])->name('chat.start');
    Route::get('/users/{id}',            [UserController::class,          'publicProfile'])->name('users.profile');

    // My Services (read)
    Route::get('/my-services-list',   [UserController::class, 'myservices'])->name('my-services.list');
    Route::get('/my-services-status', [UserController::class, 'status_myservice'])->name('my-services.status');

    // Identity Verification
    Route::get('/identity-verification',  [IdentityVerificationController::class, 'show'])->name('identity.show');
    Route::post('/identity-verification', [IdentityVerificationController::class, 'store'])->name('identity.store');

    // Posts & Ads
    Route::get('/posts',           [PostController::class,     'showmypost'])->name('posts');
    Route::get('/all-posts',       [PostController::class,     'allUserPosts'])->name('all-posts');
    Route::get('/community-posts', [PostController::class,     'communityPosts'])->name('community-posts');
    Route::get('/ads',             [AdvertisementController::class, 'userAds'])->name('ads');

    // Posts (user CRUD)
    Route::post('/posts',                        [PostController::class,     'storeUserPost'])->name('posts.store');
    Route::delete('/posts/{id}',                 [PostController::class,     'destroyUserPost'])->name('posts.destroy');

    // Likes & Comments
    Route::post('/posts/{post}/like',            [PostLikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/comments',        [CommentController::class,  'store'])->name('posts.comments.store');
    Route::delete('/comments/{comment}',         [CommentController::class,  'destroy'])->name('comments.destroy');

    // Conversations & Chat
    Route::get('/conversations',          [UserDashboardController::class, 'conversations'])->name('conversations');
    Route::get('/chat/{conversationId}',  [ChatController::class,          'show'])->name('chat');

    // Messages (AJAX)
    Route::get('/messages/unread-count',                [MessageController::class, 'unreadCount'])->name('messages.unread');
    Route::post('/messages/{conversationId}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages',                            [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversationId}',            [MessageController::class, 'index'])->name('messages.index');

    // Notifications
    Route::get('/notifications',              [NotificationController::class, 'userPage'])->name('notifications.index');
    Route::get('/notifications/data',         [NotificationController::class, 'index'])->name('notifications.data');
    Route::get('/notifications/unread',       [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::patch('/notifications/{id}/read',  [NotificationController::class, 'markAsread'])->name('notifications.read');
    Route::patch('/notifications/read-all',   [NotificationController::class, 'makeAllread'])->name('notifications.read-all');
    Route::delete('/notifications/{id}',      [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // FCM push token registration
    Route::post('/fcm-token', [FcmTokenController::class, 'store'])->name('fcm.token');
});

// ── Custom broadcasting auth (supports users, admins, super_admins guards) ────
Route::post('/skillify-broadcast-auth', function (\Illuminate\Http\Request $request) {
    $channel = $request->input('channel_name', '');

    if (str_starts_with($channel, 'private-superadmins.')) {
        $authed = $request->user('super_admins');
    } elseif (str_starts_with($channel, 'private-admins.')) {
        $authed = $request->user('admins');
    } else {
        $authed = $request->user('users');
    }

    if (!$authed) return response()->json(['error' => 'Unauthenticated.'], 403);
    $request->setUserResolver(fn () => $authed);
    return \Illuminate\Support\Facades\Broadcast::auth($request);
})->middleware('web')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class);

// ── Test broadcast (REMOVE IN PRODUCTION) ────────────────────────────────────
Route::get('/test-broadcast/{guard?}', function (string $guard = 'admins') {
    $broadcaster = app(\Illuminate\Contracts\Broadcasting\Broadcaster::class);

    if ($guard === 'super_admins') {
        $model = \App\Models\SuperAdmin::first();
        $channelPrefix = 'superadmins';
    } elseif ($guard === 'users') {
        $model = \App\Models\User::first();
        $channelPrefix = 'users';
    } else {
        $model = \App\Models\Admin::first();
        $channelPrefix = 'admins';
    }

    $channel = "private-{$channelPrefix}.{$model->id}.notifications";
    $broadcaster->broadcast(
        [$channel],
        'Illuminate\\Notifications\\Events\\BroadcastNotificationCreated',
        [
            'id'      => (string) \Illuminate\Support\Str::uuid(),
            'type'    => 'App\\Notifications\\NewRequestNotification',
            'title'   => 'اختبار إشعار فوري',
            'message' => 'هذا اختبار — وصل الإشعار بنجاح!',
        ]
    );
    return response()->json(['sent_to' => $channel, 'model' => $model->id]);
});

// ── Root ─────────────────────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');





Route::get('/test-mail', function () {
    Mail::raw('Testing Gmail SMTP', function ($message) {
        $message->to('mohamad.17.kawaji@gmail.com')
                ->subject('SMTP Test');
    });

    return 'sent';
});
