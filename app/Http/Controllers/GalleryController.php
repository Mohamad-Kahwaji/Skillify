<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return response()->json(Gallery::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'em_id' => 'required|exists:employees,id',
            'image' => 'required|string',
            'date' => 'nullable|date'
        ]);

        $gallery = Gallery::create($validated);
        return response()->json($gallery, 201);
    }

    public function show($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) return response()->json(['message' => 'Not found'], 404);
        return response()->json($gallery, 200);
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) return response()->json(['message' => 'Not found'], 404);

        $gallery->update($request->all());
        return response()->json($gallery, 200);
    }

    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) return response()->json(['message' => 'Not found'], 404);

        $gallery->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
