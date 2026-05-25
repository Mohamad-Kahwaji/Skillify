<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json(Admin::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'role' => 'string|default:admin'
        ]);

        $admin = Admin::create($validated);
        return response()->json($admin, 201);
    }

    public function show($id)
    {
        $admin = Admin::find($id);
        if (!$admin) return response()->json(['message' => 'Not found'], 404);
        return response()->json($admin, 200);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) return response()->json(['message' => 'Not found'], 404);

        $admin->update($request->all());
        return response()->json($admin, 200);
    }

    public function destroy($id)
    {
        $admin = Admin::find($id);
        if (!$admin) return response()->json(['message' => 'Not found'], 404);

        $admin->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
