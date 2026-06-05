<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\Business;
use App\Models\Post;
use App\Models\Report;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                'activeAds'        => Advertisement::where('status', 'active')->count(),
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
                'recentUsers'      => collect(),
                'pendingVerifications' => collect(),
                'recentReports'    => collect(),
            ];
        }

        return view('super_admin.dashboard', $data);
    }

    public function admins()
    {
        $admins = Admin::latest()->get();
        return view('super_admin.admins.index', compact('admins'));
    }

    public function createAdmin()
    {
        return view('super_admin.admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:admins,email',
            'phone'      => 'nullable|string|max:20',
            'password'   => 'required|min:8|confirmed',
            'role'       => 'required|in:admin,moderator',
        ]);

        Admin::create([
            ...$data,
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('super_admin.admins.index')
                         ->with('success', 'تم إنشاء الأدمن بنجاح.');
    }

    public function deleteAdmin(Admin $admin)
    {
        $admin->delete();
        return back()->with('success', 'تم حذف الأدمن.');
    }
////////////////////////////businesses/////////////////////////
    public function businesses_pending()
    {
        $businesses = Business::where('status', 'pending')->latest()->get();
        return view('', compact('businesses'));
    }
    public function businessto_approve(Business $business)
    {
        $business->update(['status' => 'approved']);
        return back()->with('success', '');
    }
    public function businessto_rejected(Business $business)
    {
        $business->update(['status' => 'rejected']);
        return back()->with('success', '');
    }
    public function businessto_pending(Business $business)
    {
        $business->update(['status' => 'pending']);
        return back()->with('success', '');
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
    public function services_pending()
    {
        $services = Service::where('status', 'pending')->latest()->get();
        return view('', compact('services   '));
    }
    public function serviceto_approve(Service $service)
    {
        $service->update(['status' => 'approved']);
        return back()->with('success', '');
    }
    public function serviceto_rejected(Service $service)
    {
        $service->update(['status' => 'rejected']);
        return back()->with('success', '');
    }
    public function serviceto_pending(Service $service)
    {
        $service->update(['status' => 'pending']);
        return back()->with('success', '');
    }

    public function services_approved(){
        $services = Service::where('status','approved')->get();
        return view('', compact('services'));
    }
    public function services_rejected(){
        $services = Service::where('status','rejected')->get();
        return view('', compact('services'));
    }
/////////////////////////////////////////////////////////////////





}
