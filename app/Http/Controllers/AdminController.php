<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\Business;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

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

        return view('admin.dashboard', $data);
    }

    public function verifications()
    {
        $pending = Business::where('status', 'pending')->latest()->get();
        return view('admin.verifications.index', compact('pending'));
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

    public function approvebusiness(int $id)
    {
        Business::findOrFail($id)->update(['status' => 'active']);
        return back()->with('success', 'Business approved.');
    }

    public function rejectbusiness(int $id)
    {
        Business::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Business rejected.');
    }
}
