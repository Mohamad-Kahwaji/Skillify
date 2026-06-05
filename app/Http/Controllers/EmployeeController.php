<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class EmployeeController extends Controller
{
    public function allemployees()
    {
        $employees = new Collection();
        return view('admin.employees.index', compact('employees'));
    }
}
