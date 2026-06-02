<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    public function getDistance($userid)
{
    $currentUser = auth('users')->user();
    $otherUser   = User::findOrFail($userId);

    $distance = $currentUser->distanceTo($otherUser);

    return response()->json([
        'distance' => $distance . ' km',
    ]);
}
    public function store(Request $request)
    {
        $user = auth('users')->user();
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'city' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

        ]);

        User::create($validated);
    
        return redirect()->route('users.index')->with('success', 'User created successfully.');

    }
    public function allusers()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function active(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'User activated.');
    }

    public function inactive(User $user)
    {
        $user->update(['status' => 'inactive']);
        return back()->with('success', 'User deactivated.');
    }
}
