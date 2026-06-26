<?php

namespace App\Http\Controllers\Auth_User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function showRegister()
    {
        if (Auth::guard('users')->check()) {
            return redirect()->route('user.dashboard');
        }
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'phone'      => 'required|string|max:20|unique:users,phone',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8|confirmed',
            'gender'     => 'required|in:male,female',
            'city'       => 'required|string|max:100',
            'birthdate'  => 'nullable|date|before:today',
        ]);

        $user = User::create([
            ...$data,
            'status' => 'active',
        ]);

        Auth::guard('users')->login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard');
    }
}
