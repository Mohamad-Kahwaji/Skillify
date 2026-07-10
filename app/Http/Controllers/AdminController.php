<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\Business;
use App\Models\Post;
use App\Models\Report;
use App\Models\Service;
use App\Models\User;
use App\Notifications\BusinessStatusNotification;
use App\Notifications\ServiceStatusNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $now = now();

            $data = [
                'totalUsers'           => User::count(),
                'newUsersThisWeek'     => User::where('created_at', '>=', $now->copy()->subDays(7))->count(),
                'activeWorkers'        => Business::where('status', 'active')->count(),
                'pendingWorkers'       => Business::where('status', 'pending')->count(),
                'postsThisMonth'       => Post::whereMonth('created_at', $now->month)->count(),
                'totalPosts'           => Post::count(),
                'pendingReports'       => Report::count(),
                'activeAds'            => Advertisement::where('status', 'active')->count(),
                'recentUsers'          => User::latest()->take(6)->get(),
                'recentPosts'          => Post::with('user')->latest()->take(5)->get(),
                'pendingVerifications' => Business::where('status', 'pending')
                                                ->with('user')->latest()->take(5)->get(),
                'recentReports'        => Report::with('user')->latest()->take(5)->get(),
            ];
        } catch (\Exception) {
            $data = [
                'totalUsers'           => 0,
                'newUsersThisWeek'     => 0,
                'activeWorkers'        => 0,
                'pendingWorkers'       => 0,
                'postsThisMonth'       => 0,
                'totalPosts'           => 0,
                'pendingReports'       => 0,
                'activeAds'            => 0,
                'recentUsers'          => collect(),
                'recentPosts'          => collect(),
                'pendingVerifications' => collect(),
                'recentReports'        => collect(),
            ];
        }

        return Inertia::render('Admin/Dashboard', $data);
    }

    public function profile()
    {
        return Inertia::render('Admin/Profile', [
            'admin' => auth('admins')->user()->only(['id', 'id_number', 'first_name', 'last_name', 'email', 'phone', 'role']),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $admin = auth('admins')->user();

        $request->validate([
            'first_name'   => 'required|string|max:60',
            'last_name'    => 'required|string|max:60',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email|max:190|unique:admins,email,' . $admin->id,
            'new_password' => 'nullable|min:8|confirmed',
        ], [
            'first_name.required'   => 'الاسم الأول مطلوب.',
            'last_name.required'    => 'الاسم الأخير مطلوب.',
            'phone.required'        => 'رقم الهاتف مطلوب.',
            'email.required'        => 'البريد الإلكتروني مطلوب.',
            'email.unique'          => 'هذا البريد الإلكتروني مستخدم من قبل حساب آخر.',
            'new_password.min'      => 'كلمة المرور الجديدة يجب أن تكون 8 أحرف على الأقل.',
            'new_password.confirmed'=> 'كلمتا المرور الجديدتان غير متطابقتين.',
        ]);

        $data = $request->only('first_name', 'last_name', 'phone', 'email');

        if ($request->filled('new_password')) {
            $data['password'] = $request->new_password;
        }

        $admin->update($data);

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

    public function verifications()
    {
        $pending = Business::where('status', 'pending')->with('user')->latest()->get();
        return Inertia::render('Admin/Verifications', ['pending' => $pending]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_number'  => 'required|string|unique:admins',
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:admins',
            'password'   => 'required|min:6',
            'phone'      => 'nullable|string',
            'role'       => 'nullable|string',
        ]);

        Admin::create($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Admin created.');
    }

    public function deleteaccountsuser(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function admin_active(Admin $admin){
        $admin->update(['status' => 'active']);
        return back()->with('success', 'Admin activated.');
    }
    public function admin_inactive(Admin $admin){
        $admin->update(['status' => 'inactive']);
        return back()->with('success', 'Admin deactivated.');   
    }

    ////////////////////////////businesses/////////////////////////
    public function businesses_pending()
    {
        $businesses = Business::where('status', 'pending')->latest()->get();
        return view('', compact('businesses'));
    }
    public function businessto_approve(Business $business)
    {
        $business->update(['status' => 'active']);
        $business->user?->syncBusinessRole();
        $business->user?->notify(new BusinessStatusNotification('approved', $business->name));
        return back()->with('success', 'تم قبول حساب الأعمال.');
    }

    public function businessto_rejected(Business $business)
    {
        $business->update(['status' => 'rejected']);
        $business->user?->syncBusinessRole();
        $business->user?->notify(new BusinessStatusNotification('rejected', $business->name));
        return back()->with('success', 'تم رفض حساب الأعمال.');
    }

    public function businessto_pending(Business $business)
    {
        $business->update(['status' => 'pending']);
        $business->user?->syncBusinessRole();
        return back()->with('success', 'تم إعادة الطلب لقيد الانتظار.');
    }

    public function businesses_approved(){
        $businesses = Business::where('status','approved')->get();
        return view('', compact('businesses'));
    }
    public function businesses_rejected(){
        $businesses = Business::where('status','rejected')->get();
        return view('', compact('businesses'));
    }
/////////////////////////////////////////////////////////////////


//////////////////////////services/////////////////////////
    public function serviceRequests()
    {
        $services = Service::with(['user','category','subcategory','city'])
            ->latest()->get();
        return Inertia::render('Admin/ServiceRequests', ['services' => $services]);
    }

    public function businessRequests()
    {
        $businesses = Business::with('user')->latest()->get();
        return Inertia::render('Admin/BusinessRequests', ['businesses' => $businesses]);
    }

    public function serviceto_approve(Service $service)
    {
        $service->update(['status' => 'approved']);
        $service->user?->notify(new ServiceStatusNotification('approved', $service->name));
        return back()->with('success', 'تم قبول الخدمة.');
    }

    public function serviceto_rejected(Service $service)
    {
        $service->update(['status' => 'rejected']);
        $service->user?->notify(new ServiceStatusNotification('rejected', $service->name));
        return back()->with('success', 'تم رفض الخدمة.');
    }

    public function serviceto_pending(Service $service)
    {
        $service->update(['status' => 'pending']);
        $service->user?->notify(new ServiceStatusNotification('pending', $service->name));
        return back()->with('success', 'تم إعادة الخدمة لقيد المراجعة.');
    }

    public function services_approved(){
        $services = Service::where('status','approved')->get();
        return view('', compact('services'));
    }
    public function services_rejected(){
        $services = Service::where('status','rejected')->get();
        return view('', compact('services'));
    }

    // ── Notifications ─────────────────────────────────────────────────────────
    public function notifications()
    {
        $admin         = auth('admins')->user();
        $notifications = $admin->notifications()->latest()->paginate(20);

        return Inertia::render('Admin/Notifications', [
            'notifications' => $notifications,
            'unread_count'  => $admin->unreadNotifications()->count(),
        ]);
    }

    public function markNotificationRead($id)
    {
        auth('admins')->user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    }

    public function markAllNotificationsRead()
    {
        auth('admins')->user()->unreadNotifications->markAsRead();
        return back();
    }
/////////////////////////////////////////////////////////////////

}
