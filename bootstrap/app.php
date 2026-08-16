<?php

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\ConfirmAdminPassword;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HasBusinessMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\RequireVerifiedIdentity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [HandleInertiaRequests::class]);
        $middleware->alias([
            'auth_admin'         => AuthMiddleware::class,
            'auth_super_admin'   => SuperAdminMiddleware::class,
            'auth_user'          => UserMiddleware::class,
            'confirm_admin_password' => ConfirmAdminPassword::class,
            'has_business'           => HasBusinessMiddleware::class,
            'verified_identity'      => RequireVerifiedIdentity::class,
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (UnauthorizedException $exception, Request $request) {
            $message = 'عذرًا، هذا الإجراء ليس ضمن صلاحياتك.';

            if ($request->expectsJson() || $request->header('X-Inertia')) {
                return response()->json(['message' => $message], 403);
            }

            return response('<div dir="rtl" style="font-family:Arial,sans-serif;max-width:520px;margin:15vh auto;padding:32px;text-align:center;border:1px solid #FECACA;border-radius:16px;background:#FEF2F2;color:#991B1B"><h2>عذرًا، هذا الإجراء ليس ضمن صلاحياتك.</h2><p>تواصل مع المسؤول إذا كنت تحتاج إلى صلاحية إضافية.</p></div>', 403);
        });
    })->create();
