<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showlogin(){
        return view('');
    }

    public function login(LoginRequest $request){
        if(Auth::guard('users')->attempt($request->only('phone', 'password'))){
            return back()->withErrors([
                'phone' => 'Invalid credentials.',
            ]);
        }
        $request->session()->regenerate();
            return redirect()->route('');
    }

}
