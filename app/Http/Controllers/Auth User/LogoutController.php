<?php

namespace App\Http\Controllers\Logout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    Public function logout(Request $request){
        Auth::guard('users')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('');
    }
}
