<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        return response()->json(Advertisement::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'price' => 'nullable|numeric',
            'status' => 'string|default:active'
        ]);

        $advertisement = Advertisement::create($validated);
        return response()->json($advertisement, 201);
    }

    public function show($id)
    {
        $advertisement = Advertisement::find($id);
        if (!$advertisement) return response()->json(['message' => 'Not found'], 404);
        return response()->json($advertisement, 200);
    }

    public function update(Request $request, $id)
    {
        $advertisement = Advertisement::find($id);
        if (!$advertisement) return response()->json(['message' => 'Not found'], 404);

        $advertisement->update($request->all());
        return response()->json($advertisement, 200);
    }

    public function destroy($id)
    {
        $advertisement = Advertisement::find($id);
        if (!$advertisement) return response()->json(['message' => 'Not found'], 404);

        $advertisement->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
