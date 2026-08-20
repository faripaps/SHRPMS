<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['headOfDepartment', 'positions', 'employees'])->get();
        $positions = Position::with('department')->get();
        $employees = Employee::where('status', 'Active')->get();

        return view('departments.index', compact('departments', 'positions', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|unique:departments,code',
            'description' => 'nullable|string',
            'branch' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'head_of_department_id' => 'nullable|exists:employees,id',
        ]);

        Department::create($validated);

        return redirect()->back()->with('success', 'Department created successfully!');
    }
}
