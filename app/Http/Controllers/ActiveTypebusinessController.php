<?php

namespace App\Http\Controllers;

use App\Models\ActiveTypebusiness;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActiveTypebusinessController extends Controller
{
    public function index()
    {
        $types = ActiveTypebusiness::latest()->get();
        return Inertia::render('Admin/ActiveTypeBusinesses', ['types' => $types]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:active_typebusinesses,name',
        ]);
        ActiveTypebusiness::create($validated);
        return back()->with('success', 'تم إضافة نوع العمل.');
    }

    public function update(Request $request, int $id)
    {
        $type = ActiveTypebusiness::findOrFail($id);
        $validated = $request->validate([
            'name' => "required|string|max:100|unique:active_typebusinesses,name,{$id}",
        ]);
        $type->update($validated);
        return back()->with('success', 'تم تعديل نوع العمل.');
    }

    public function destroy(int $id)
    {
        ActiveTypebusiness::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف نوع العمل.');
    }
}
