<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Permissions — admins guard ────────────────────────────────────────
        $adminPerms = [
            'users.view', 'users.activate', 'users.deactivate',
            'users.delete', 'users.view_no_services',

            'businesses.view', 'businesses.approve', 'businesses.reject',
            'businesses.delete', 'businesses.show',

            'verifications.view', 'verifications.approve', 'verifications.reject',

            'posts.view_all', 'posts.delete',
            'reports.view',

            'ads.view', 'ads.create', 'ads.edit', 'ads.delete',

            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'subcategories.view', 'subcategories.create', 'subcategories.edit', 'subcategories.delete',

            'active_types.view', 'active_types.create', 'active_types.delete',
            'active_type_businesses.view', 'active_type_businesses.create', 'active_type_businesses.delete',

            'cities.view', 'cities.create', 'cities.edit', 'cities.delete',

            'services.view', 'services.toggle', 'services.delete',

            'blocked.view', 'blocked.create', 'blocked.delete',
            'employees.view',
        ];

        foreach ($adminPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admins']);
        }

        // ── Permissions — super_admins guard (all admin perms + own perms) ───
        $superAdminPerms = array_merge($adminPerms, [
            'admins.view', 'admins.create', 'admins.delete',
        ]);
        foreach ($superAdminPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'super_admins']);
        }

        // ── Permissions — users guard ─────────────────────────────────────────
        $userPerms = [
            'profile.view', 'profile.update',
            'business.create', 'business.update',
            'my_services.view', 'my_services.create', 'my_services.edit',
            'my_services.delete', 'my_services.view_status',
            'posts.create', 'posts.view_own',
            'explore.view', 'services.browse',
            'chat.view', 'messages.send',
        ];

        foreach ($userPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'users']);
        }

        // ── Roles — admins guard ──────────────────────────────────────────────

        // admin: كل الصلاحيات
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admins']);
        $admin->syncPermissions(Permission::where('guard_name', 'admins')->get());

        // content_moderator: منشورات وبلاغات
        $contentMod = Role::firstOrCreate(['name' => 'content_moderator', 'guard_name' => 'admins']);
        $contentMod->syncPermissions(
            Permission::where('guard_name', 'admins')
                ->whereIn('name', ['posts.view_all', 'posts.delete', 'reports.view'])
                ->get()
        );

        // verifier: مراجعة حسابات الأعمال
        $verifier = Role::firstOrCreate(['name' => 'verifier', 'guard_name' => 'admins']);
        $verifier->syncPermissions(
            Permission::where('guard_name', 'admins')
                ->whereIn('name', [
                    'businesses.view', 'businesses.approve', 'businesses.reject', 'businesses.show',
                    'verifications.view', 'verifications.approve', 'verifications.reject',
                ])
                ->get()
        );

        // support: عرض فقط
        $support = Role::firstOrCreate(['name' => 'support', 'guard_name' => 'admins']);
        $support->syncPermissions(
            Permission::where('guard_name', 'admins')
                ->whereIn('name', [
                    'users.view', 'users.view_no_services',
                    'businesses.view', 'businesses.show',
                    'verifications.view',
                    'posts.view_all', 'reports.view',
                    'ads.view', 'categories.view', 'subcategories.view',
                    'active_types.view', 'active_type_businesses.view',
                    'cities.view', 'services.view',
                    'blocked.view', 'employees.view',
                ])
                ->get()
        );

        // ── Roles — super_admins guard ────────────────────────────────────────
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'super_admins']);
        $superAdmin->syncPermissions(Permission::where('guard_name', 'super_admins')->get());

        // ── Roles — users guard ───────────────────────────────────────────────

        // user عادي: بدون إنشاء/تعديل خدمات وحساب أعمال
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'users']);
        $user->syncPermissions(
            Permission::where('guard_name', 'users')
                ->whereNotIn('name', ['business.update', 'my_services.create', 'my_services.edit'])
                ->get()
        );

        // business_owner: كل صلاحيات المستخدم
        $bizOwner = Role::firstOrCreate(['name' => 'business_owner', 'guard_name' => 'users']);
        $bizOwner->syncPermissions(Permission::where('guard_name', 'users')->get());

        $this->command->info('✓ Roles & Permissions seeded.');
    }
}
