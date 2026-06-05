<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->latest()->get();
        $categories    = Category::all();
        return view('admin.subcategories.index', compact('subcategories', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'     => 'required|string|max:255',
            'name_en'     => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        Subcategory::create($data);
        return redirect()->route('admin.subcategories.index')->with('success', 'تم إضافة التصنيف الفرعي.');
    }

    public function edit(int $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $categories  = Category::all();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name_ar'     => 'required|string|max:255',
            'name_en'     => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        Subcategory::findOrFail($id)->update($data);
        return redirect()->route('admin.subcategories.index')->with('success', 'تم تعديل التصنيف الفرعي.');
    }

    public function destroy(int $id)
    {
        Subcategory::findOrFail($id)->delete();
        return redirect()->route('admin.subcategories.index')->with('success', 'تم حذف التصنيف الفرعي.');
    }
}
