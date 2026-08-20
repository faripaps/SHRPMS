<?php

namespace App\Http\Controllers;

use App\Models\LifecycleEvent;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class LifecycleController extends Controller
{
    public function index()
    {
        $events = LifecycleEvent::with('employee.department')
            ->latest('effective_date')
            ->paginate(15);

        $employees = Employee::whereIn('status', ['Active', 'Probation', 'Contract'])->get();
        $departments = Department::all();
        $positions = Position::all();

        return view('lifecycle.index', compact('events', 'employees', 'departments', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'event_type' => 'required|string',
            'effective_date' => 'required|date',
            'new_position_id' => 'nullable|exists:positions,id',
            'new_department_id' => 'nullable|exists:departments,id',
            'new_salary' => 'nullable|numeric|min:0',
            'new_status' => 'nullable|string',
            'description' => 'required|string',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $prevValue = [];
        $newValue = [];

        if ($request->event_type === 'Promotion' || $request->event_type === 'Transfer') {
            if ($request->new_position_id) {
                $pos = Position::find($request->new_position_id);
                $prevValue['position'] = $employee->position?->title;
                $newValue['position'] = $pos->title;
                $employee->position_id = $pos->id;
            }
            if ($request->new_department_id) {
                $dept = Department::find($request->new_department_id);
                $prevValue['department'] = $employee->department?->name;
                $newValue['department'] = $dept->name;
                $employee->department_id = $dept->id;
            }
        }

        if ($request->filled('new_salary') && $request->new_salary > 0) {
            $prevValue['basic_salary'] = $employee->basic_salary;
            $newValue['basic_salary'] = $request->new_salary;
            $employee->basic_salary = $request->new_salary;
        }

        if ($request->filled('new_status')) {
            $prevValue['status'] = $employee->status;
            $newValue['status'] = $request->new_status;
            $employee->status = $request->new_status;
        }

        $employee->save();

        LifecycleEvent::create([
            'employee_id' => $employee->id,
            'event_type' => $request->event_type,
            'effective_date' => $request->effective_date,
            'previous_value' => json_encode($prevValue),
            'new_value' => json_encode($newValue),
            'description' => $request->description,
            'performed_by' => 'HR Administrator'
        ]);

        return redirect()->back()->with('success', "Lifecycle event '{$request->event_type}' processed successfully!");
    }
}
