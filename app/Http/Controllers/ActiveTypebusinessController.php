<?php

namespace App\Http\Controllers;

use App\Models\ActiveTypebusiness;
use Illuminate\Http\Request;

class ActiveTypebusinessController extends Controller
{
    public function index(){
        $types = ActiveTypebusiness::all();
        return view('admin.dashboard', compact('types'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
        ]);

        ActiveTypebusiness::firstOrCreate ($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Active type business created.');
    }
}
