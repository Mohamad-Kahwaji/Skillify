<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json(Admin::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_number' => 'required|string|unique:admins',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'role' => 'string|default:admin'
        ]);

        $admin = Admin::create($validated);
        return response()->json($admin, 201);
    }


    public function details($id)
    {
        $users = User::get([
            'id',
            'name',
            'email',
        ]);
        $businesses = Business::get([
            'number',
            'location',
            'description',
            'activity'
        ]);
        return redirect()->route('',compact('businesses','users'));

    }



    public function deleteaccountsuser($id){
        $user = User::findOrFail($id);
        $user->delete()->with('businesses');
        return redirect()->route('');
    }

    public function reviewbusiness($id){
        $business = Business::findOrFail($id);
        $business->update([
            'status' => 'pending'
        ]);
        return redirect()->route('');
    }
    public function approvebusiness($id){
        $business = Business::findOrFail($id);
        $business->update([
            'status' => 'active'
        ]);
        return redirect()->route('');
    }
    public function rejectbusiness($id){
        $business = Business::findOrFail($id);
        $business->update([
            'status' => 'rejected'
        ]);
        return redirect()->route('');
    }

    /*ublic function deleteaccountbusiness($id){
        $business = Business::findOrFail($id);
        $business->delete();
        return redirect()->route('');
    }*/
}
