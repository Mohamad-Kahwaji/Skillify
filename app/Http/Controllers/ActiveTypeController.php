<?php

namespace App\Http\Controllers;

use App\Models\ActiveType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActiveTypeController extends Controller
{
    public function index()
    {
        $types = ActiveType::latest()->get();
        return Inertia::render('Admin/ActiveTypes', ['types' => $types]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',
        ]);
        ActiveType::firstOrCreate($validated);
        return redirect()->route('admin.active_types.index')->with('success', 'تم إضافة نوع النشاط.');
    }

    public function destroy(int $id)
    {
        ActiveType::findOrFail($id)->delete();
        return redirect()->route('admin.active_types.index')->with('success', 'تم حذف نوع النشاط.');
    }
}
