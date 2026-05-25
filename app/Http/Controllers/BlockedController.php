<?php

namespace App\Http\Controllers;

use App\Models\Blocked;
use Illuminate\Http\Request;

class BlockedController extends Controller
{
    public function index()
    {
        return response()->json(Blocked::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'user_id' => 'required|exists:users,id',
            'reason' => 'nullable|string',
            'blocker_date' => 'nullable|date',
            'status' => 'string|default:active'
        ]);

        $blocked = Blocked::create($validated);
        return response()->json($blocked, 201);
    }

    public function show($id)
    {
        $blocked = Blocked::find($id);
        if (!$blocked) return response()->json(['message' => 'Not found'], 404);
        return response()->json($blocked, 200);
    }

    public function update(Request $request, $id)
    {
        $blocked = Blocked::find($id);
        if (!$blocked) return response()->json(['message' => 'Not found'], 404);

        $blocked->update($request->all());
        return response()->json($blocked, 200);
    }

    public function destroy($id)
    {
        $blocked = Blocked::find($id);
        if (!$blocked) return response()->json(['message' => 'Not found'], 404);

        $blocked->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
