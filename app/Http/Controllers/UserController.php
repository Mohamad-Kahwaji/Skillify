<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function getDistance($userid)
{
    $currentUser = auth('users')->user();
    $otherUser   = User::findOrFail($userid);

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
        return Inertia::render('Admin/Users', ['users' => $users]);
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
    public function services_users(){
        $users = User::whereNotIn('id', function($query) {
            $query->select('user_id')->from('services');
        })->latest()->get();
        return Inertia::render('Admin/NoServicesUsers', ['users' => $users]);
    }

    public function myservices(){
        $services = Service::where('user_id', auth('users')->id())
            ->with(['category','subcategory','city'])
            ->latest()
            ->get();
        return Inertia::render('User/MyServices', ['services' => $services]);
    }

    public function status_myservice(){
        return redirect()->route('user.my-services.list', ['filter' => 'pending']);
    }

}
