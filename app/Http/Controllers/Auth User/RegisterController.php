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
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'phone' => $request->phone,
            'password' => $request->password,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'city' => $request->city,
            'email' => $request->email,


        ]);
        Auth::guard('users')->Login($admin);
        return  redirect()->route('');
    }
}
