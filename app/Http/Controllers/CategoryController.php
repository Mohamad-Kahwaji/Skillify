<?php

namespace App\Http\Controllers;

use App\Models\ActiveTypebusiness;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories      = Category::with('activeTypebusiness')->latest()->get();
        $businessTypes   = ActiveTypebusiness::all();
        return view('admin.categories.index', compact('categories', 'businessTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'                => 'required|string|max:255',
            'name_en'                => 'required|string|max:255',
            'active_typebusiness_id' => 'required|exists:active_typebusinesses,id',
        ]);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة التصنيف.');
    }

    public function edit(int $id)
    {
        $category      = Category::findOrFail($id);
        $businessTypes = ActiveTypebusiness::all();
        return view('admin.categories.edit', compact('category', 'businessTypes'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name_ar'                => 'required|string|max:255',
            'name_en'                => 'required|string|max:255',
            'active_typebusiness_id' => 'required|exists:active_typebusinesses,id',
        ]);
        Category::findOrFail($id)->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'تم تعديل التصنيف.');
    }

    public function destroy(int $id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف التصنيف.');
    }
}
