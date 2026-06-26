<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\Business;
use App\Models\IdentityVerification;
use App\Models\Post;
use App\Models\Report;
use App\Models\Service;
use App\Models\User;
use App\Services\GeminiIdentityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        try {
            $now = now();
            $data = [
                'admins'           => Admin::latest()->get(),
                'totalUsers'       => User::count(),
                'newUsersThisWeek' => User::where('created_at', '>=', $now->copy()->subDays(7))->count(),
                'totalBiz'         => Business::count(),
                'activeWorkers'    => Business::where('status', 'active')->count(),
                'pendingWorkers'   => Business::where('status', 'pending')->count(),
                'totalPosts'       => Post::count(),
                'postsThisMonth'   => Post::whereMonth('created_at', $now->month)->count(),
                'pendingReports'   => Report::count(),
                'activeAds'        => Advertisement::where('status', 'approved')->count(),
                'totalRoles'       => Role::count(),
                'totalPermissions' => Permission::count(),
                'recentUsers'      => User::latest()->take(5)->get(),
                'pendingVerifications' => Business::where('status', 'pending')->with('user')->latest()->take(4)->get(),
                'recentReports'    => Report::with('user')->latest()->take(4)->get(),
            ];
        } catch (\Exception) {
            $data = [
                'admins'           => collect(),
                'totalUsers'       => 0,
                'newUsersThisWeek' => 0,
                'totalBiz'         => 0,
                'activeWorkers'    => 0,
                'pendingWorkers'   => 0,
                'totalPosts'       => 0,
                'postsThisMonth'   => 0,
                'pendingReports'   => 0,
                'activeAds'        => 0,
                'totalRoles'       => 0,
                'totalPermissions' => 0,
                'recentUsers'      => collect(),
                'pendingVerifications' => collect(),
                'recentReports'    => collect(),
            ];
        }

        return Inertia::render('SuperAdmin/Dashboard', $data);
    }

    public function admins()
    {
        $admins = Admin::with('roles.permissions')->latest()->get();
        $roles  = Role::where('guard_name', 'admins')
            ->with('permissions:id,name')
            ->get(['id', 'name']);
        return Inertia::render('SuperAdmin/Admins', [
            'admins' => $admins,
            'roles'  => $roles,
        ]);
    }

    public function createAdmin()
    {
        $roles = Role::where('guard_name', 'admins')
            ->with('permissions:id,name')
            ->get(['id', 'name']);
        return Inertia::render('SuperAdmin/Admins', [
            'admins' => Admin::with('roles.permissions')->latest()->get(),
            'roles'  => $roles,
        ]);
    }

    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'first_name'     => 'required|string|max:50',
            'last_name'      => 'required|string|max:50',
            'email'          => 'required|email|unique:admins,email',
            'phone'          => 'nullable|string|max:20',
            'password'       => 'required|min:8|confirmed',
            'role_id'        => 'nullable|exists:roles,id',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $admin = Admin::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'password'   => Hash::make($data['password']),
            'id_number'  => 'ADM-' . strtoupper(substr(md5(uniqid()), 0, 8)),
        ]);

        if (!empty($data['role_id'])) {
            $role       = Role::find($data['role_id']);
            $allPerms   = $role->permissions->pluck('id')->toArray();
            $selected   = $data['permission_ids'] ?? $allPerms;

            if (count($selected) === count($allPerms)) {
                // Full role assigned — standard
                $admin->assignRole($role);
            } elseif (count($selected) > 0) {
                // Partial — assign direct permissions only (no role)
                $admin->givePermissionTo(
                    \Spatie\Permission\Models\Permission::whereIn('id', $selected)->get()
                );
            }
            // If 0 selected → no role, no permissions
        }

        return redirect()->route('super_admin.admins.index')
                         ->with('success', 'تم إنشاء المشرف بنجاح.');
    }

    public function assignAdminRole(Request $request, Admin $admin)
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);
        $role = Role::find($request->role_id);
        $admin->syncRoles([$role]);
        return back()->with('success', 'تم تعيين الدور بنجاح.');
    }

    public function revokeAdminRole(Admin $admin)
    {
        $admin->syncRoles([]);
        return back()->with('success', 'تم سحب الأدوار.');
    }

    public function deleteAdmin(Admin $admin)
    {
        $admin->delete();
        return back()->with('success', 'تم حذف المشرف.');
    }
    // ── Users ──────────────────────────────────────────────────────────────────
    public function users()
    {
        $users = User::withCount(['posts', 'services', 'comments'])
            ->with('businesses:id,user_id,status,name')
            ->latest()
            ->get();
        return Inertia::render('SuperAdmin/Users', ['users' => $users]);
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    // ── Businesses ──────────────────────────────────────────────────────────────
    public function businesses()
    {
        $businesses = Business::with('user')->latest()->get();
        return Inertia::render('SuperAdmin/Businesses', ['businesses' => $businesses]);
    }

    public function businessto_approve(Business $business)
    {
        $business->update(['status' => 'approved']);
        return back()->with('success', 'Business approved.');
    }

    public function businessto_rejected(Business $business)
    {
        $business->update(['status' => 'rejected']);
        return back()->with('success', 'Business rejected.');
    }

    public function businessto_pending(Business $business)
    {
        $business->update(['status' => 'pending']);
        return back()->with('success', 'Business set to pending.');
    }

    public function destroyBusiness(Business $business)
    {
        $business->delete();
        return back()->with('success', 'Business deleted.');
    }

    // ── Services ────────────────────────────────────────────────────────────────
    public function services()
    {
        $services = Service::with(['user', 'category', 'subcategory', 'city'])->latest()->get();
        return Inertia::render('SuperAdmin/Services', ['services' => $services]);
    }

    public function serviceto_approve(Service $service)
    {
        $service->update(['status' => 'approved']);
        return back()->with('success', 'Service approved.');
    }

    public function serviceto_rejected(Service $service)
    {
        $service->update(['status' => 'rejected']);
        return back()->with('success', 'Service rejected.');
    }

    public function serviceto_pending(Service $service)
    {
        $service->update(['status' => 'pending']);
        return back()->with('success', 'Service set to pending.');
    }

    public function destroyService(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }

    // ── Ads ─────────────────────────────────────────────────────────────────────
    public function ads()
    {
        $ads = Advertisement::with('user')->latest()->get();
        return Inertia::render('SuperAdmin/Ads', ['ads' => $ads]);
    }

    public function toggleAd(Advertisement $ad)
    {
        $ad->update(['status' => $ad->status === 'approved' ? 'pending' : 'approved']);
        return back()->with('success', 'Ad status updated.');
    }

    public function destroyAd(Advertisement $ad)
    {
        $ad->delete();
        return back()->with('success', 'Ad deleted.');
    }

    // ── Identity Verifications ───────────────────────────────────────────────────
    public function identityVerifications()
    {
        $verifications = IdentityVerification::with(['user', 'reviewer'])->latest()->get();
        return Inertia::render('SuperAdmin/IdentityVerifications', [
            'verifications' => $verifications,
        ]);
    }

    public function approveIdentity(IdentityVerification $verification)
    {
        // reviewed_by references admins table; super_admins is a separate table — keep null
        $verification->update([
            'status'           => 'approved',
            'reviewed_by'      => null,
            'reviewed_at'      => now(),
            'rejection_reason' => null,
        ]);
        return back()->with('success', 'تم قبول طلب التوثيق.');
    }

    public function rejectIdentity(Request $request, IdentityVerification $verification)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $verification->update([
            'status'           => 'rejected',
            'reviewed_by'      => null,
            'reviewed_at'      => now(),
            'rejection_reason' => $request->reason,
        ]);
        return back()->with('success', 'تم رفض طلب التوثيق.');
    }

    public function pendingIdentity(IdentityVerification $verification)
    {
        $verification->update([
            'status'           => 'pending',
            'reviewed_by'      => null,
            'reviewed_at'      => null,
            'rejection_reason' => null,
        ]);
        return back()->with('success', 'تمت إعادة الطلب إلى قيد المراجعة.');
    }

    public function analyseAllIdentities(GeminiIdentityService $gemini)
    {
        set_time_limit(300);

        $verifications = IdentityVerification::where('status', 'pending')->get();

        $done = 0;
        $failed = 0;

        foreach ($verifications as $verification) {
            try {
                $result = $gemini->analyse($verification);
                $verification->update([
                    'match_score'    => $result['match_score'] ?? null,
                    'extracted_data' => $result,
                ]);
                $done++;
                // Brief pause to avoid rate limiting
                usleep(600000);
            } catch (\RuntimeException $e) {
                $failed++;
            }
        }

        $msg = "تم تحليل {$done} طلب بالذكاء الاصطناعي.";
        if ($failed > 0) $msg .= " فشل تحليل {$failed} طلب.";

        return back()->with('success', $msg);
    }

    public function analyseIdentityWithAi(IdentityVerification $verification, GeminiIdentityService $gemini)
    {
        try {
            $result = $gemini->analyse($verification);
            $verification->update([
                'match_score'    => $result['match_score'] ?? null,
                'extracted_data' => $result,
            ]);
            return back()->with('success', sprintf(
                'تم التحليل بالذكاء الاصطناعي. نسبة التطابق: %d%% — التوصية: %s',
                $result['match_score'] ?? 0,
                match($result['verdict'] ?? '') {
                    'approved' => '✅ قبول',
                    'rejected' => '❌ رفض',
                    default    => '⚠️ مراجعة يدوية',
                }
            ));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ── Posts ────────────────────────────────────────────────────────────────────
    public function posts()
    {
        $posts = Post::with('user')
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get();
        return Inertia::render('SuperAdmin/Posts', ['posts' => $posts]);
    }

    public function destroyPost(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted.');
    }

    // ── Notifications ─────────────────────────────────────────────────────────
    public function notifications()
    {
        $admin         = auth('super_admins')->user();
        $notifications = $admin->notifications()->latest()->paginate(20);

        return Inertia::render('SuperAdmin/Notifications', [
            'notifications' => $notifications,
            'unread_count'  => $admin->unreadNotifications()->count(),
        ]);
    }

    public function markNotificationRead($id)
    {
        auth('super_admins')->user()->notifications()->findOrFail($id)->markAsRead();
        return back()->with('success', 'تم تعيين الإشعار كمقروء.');
    }

    public function markAllNotificationsRead()
    {
        auth('super_admins')->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'تم تعيين جميع الإشعارات كمقروءة.');
    }


}
