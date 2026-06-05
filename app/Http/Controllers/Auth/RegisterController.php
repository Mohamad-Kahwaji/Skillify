<?php

    namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegister(){
        return view('');
    }

    public function register(RegisterRequest $request){
        $admin = Admin::Create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        Auth::guard('admins')->Login($admin);
        return  redirect()->route('');
    }
}
