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
            'name' => 'required|string|max:100|unique:active_types,name',
        ]);
        ActiveType::create($validated);
        return back()->with('success', 'تم إضافة نوع النشاط.');
    }

    public function update(Request $request, int $id)
    {
        $type = ActiveType::findOrFail($id);
        $validated = $request->validate([
            'name' => "required|string|max:100|unique:active_types,name,{$id}",
        ]);
        $type->update($validated);
        return back()->with('success', 'تم تعديل نوع النشاط.');
    }

    public function destroy(int $id)
    {
        ActiveType::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف نوع النشاط.');
    }
}
