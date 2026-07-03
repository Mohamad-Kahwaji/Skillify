<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['token' => 'required|string|max:500']);

        $user = Auth::guard('users')->user()
             ?? Auth::guard('admins')->user()
             ?? Auth::guard('super_admins')->user();

        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);

        $user->update(['fcm_token' => $request->token]);

        return response()->json(['success' => true]);
    }
}
