<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Blocked;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBlockedController extends Controller
{
    public function index()
    {
        $blocked = Blocked::with(['user', 'admin'])->latest()->get();
        return view('admin.blocked.index', compact('blocked'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.blocked.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'reason'       => 'required|string|max:500',
            'blocker_date' => 'nullable|date',
        ]);

        Blocked::create([
            'admin_id'     => Auth::guard('admins')->id(),
            'user_id'      => $data['user_id'],
            'reason'       => $data['reason'],
            'blocker_date' => $data['blocker_date'] ?? now(),
            'status'       => 'active',
        ]);

        // Mark user as inactive
        User::findOrFail($data['user_id'])->update(['status' => 'inactive']);

        return redirect()->route('admin.blocked.index')->with('success', 'User blocked successfully.');
    }

    public function destroy(int $id)
    {
        $blocked = Blocked::findOrFail($id);

        // Reactivate user
        User::findOrFail($blocked->user_id)->update(['status' => 'active']);

        $blocked->delete();
        return redirect()->route('admin.blocked.index')->with('success', 'User unblocked successfully.');
    }
}
