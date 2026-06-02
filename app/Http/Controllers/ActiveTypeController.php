<?php

namespace App\Http\Controllers;

use App\Models\ActiveType;
use Illuminate\Http\Request;

class ActiveTypeController extends Controller
{
    public function index(){
        $types = ActiveType::all();
        return view('admin.dashboard', compact('types'));
    }
    public function store(Request $request){
        $validated = $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
        ]);

        ActiveType::firstOrCreate ($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Active type created.');
    }
}
