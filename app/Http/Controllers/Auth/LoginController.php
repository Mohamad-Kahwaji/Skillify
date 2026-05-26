<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showlogin(){
        return view('');
    }

    public function login(LoginRequest $request){
        if(Auth::attempt($request->only('email', 'password'))){
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ]);
        }
        $request->session()->regenerate();
            return redirect()->route('');
    }


    Public function logout(Request $request){
        Auth::Logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('');
    }
}
