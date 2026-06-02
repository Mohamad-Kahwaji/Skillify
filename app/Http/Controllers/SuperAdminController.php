<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\Business;
use App\Models\Post;
use App\Models\Report;
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
}
