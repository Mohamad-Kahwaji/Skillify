<?php

namespace App\Http\Controllers;

use App\Models\ActiveTypebusiness;
use Illuminate\Http\Request;

class ActiveTypebusinessController extends Controller
{
    public function index()
    {
        $types = ActiveTypebusiness::latest()->get();
        return view('admin.active_typebusinesses.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',
        ]);
        ActiveTypebusiness::firstOrCreate($validated);
        return redirect()->route('admin.active_typebusinesses.index')->with('success', 'تم إضافة نوع العمل.');
    }

    public function destroy(int $id)
    {
        ActiveTypebusiness::findOrFail($id)->delete();
        return redirect()->route('admin.active_typebusinesses.index')->with('success', 'تم حذف نوع العمل.');
    }
}
