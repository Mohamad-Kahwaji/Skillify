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
            'name' => 'required|string',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string',
            'position' => 'required|string',
            'department' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'hire_date' => 'nullable|date',
            'status' => 'string|default:active'
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
