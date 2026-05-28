<?php

    namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegister(){
        return view('');
    }

    public function register(RegisterRequest $request){
        $admin = User::Create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        Auth::guard('users')->Login($admin);
        return  redirect()->route('');
    }
}
