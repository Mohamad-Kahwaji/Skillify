<?php

namespace App\Http\Controllers;

use App\Models\ActiveTypebusiness;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category.activeTypebusiness')->latest()->get();
        $categories    = Category::with('activeTypebusiness')->orderBy('name')->get();
        $businessTypes = ActiveTypebusiness::orderBy('name')->get();
        return Inertia::render('Admin/Subcategories', [
            'subcategories' => $subcategories,
            'categories'    => $categories,
            'businessTypes' => $businessTypes,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        Subcategory::create($data);
        return back()->with('success', 'تم إضافة التصنيف الفرعي.');
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
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        Subcategory::findOrFail($id)->update($data);
        return back()->with('success', 'تم تعديل التصنيف الفرعي.');
    }

    public function destroy(int $id)
    {
        Subcategory::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف التصنيف الفرعي.');
    }
}
