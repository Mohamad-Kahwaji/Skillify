<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showusers(){
        return view('');
    }
    public function allusers(){
        $users = User::all();
        return view('',compact('users'));
    }
}
