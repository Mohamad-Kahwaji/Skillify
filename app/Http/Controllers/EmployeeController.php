<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'profession' => 'nullable|string',
            'national_id' => 'required|string|unique:employees',
            'id_card_photo' => 'nullable|string',
            'emp_image' => 'nullable|string'
        ]);

        $employee = Employee::create($validated);
        return response()->json($employee, 201);
    }

    public function show($id)
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['message' => 'Not found'], 404);
        return response()->json($employee, 200);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['message' => 'Not found'], 404);

        $employee->update($request->all());
        return response()->json($employee, 200);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['message' => 'Not found'], 404);

        $employee->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
