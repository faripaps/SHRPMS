<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\LifecycleEvent;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->latest('id')->paginate(10);
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();

        // Auto generate next employee number
        $year = Carbon::now()->year;
        $nextNum = Employee::count() + 1;
        $autoNumber = sprintf("EMP-%d-%03d", $year, $nextNum);

        return view('employees.create', compact('departments', 'positions', 'autoNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|unique:employees,employee_number',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'national_id' => 'required|unique:employees,national_id',
            'address' => 'nullable|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'emergency_contact_relationship' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'date_hired' => 'required|date',
            'employment_type' => 'required|string',
            'status' => 'required|string',
            'salary_grade' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'required|numeric|min:0',
            'transport_allowance' => 'required|numeric|min:0',
        ]);

        $employee = Employee::create($validated);

        // Record Onboarding Lifecycle Event
        LifecycleEvent::create([
            'employee_id' => $employee->id,
            'event_type' => 'Onboarding',
            'effective_date' => $employee->date_hired,
            'new_value' => json_encode(['position' => $employee->position->title, 'salary' => $employee->basic_salary]),
            'description' => 'Employee registered and onboarded into organizational workforce database.',
            'performed_by' => 'HR Administrator'
        ]);

        // Seed Leave Balances
        $leaveTypes = LeaveType::all();
        foreach ($leaveTypes as $lt) {
            LeaveBalance::create([
                'employee_id' => $employee->id,
                'leave_type_id' => $lt->id,
                'year' => Carbon::now()->year,
                'total_entitled' => $lt->default_days_per_year,
                'used_days' => 0,
                'pending_days' => 0,
            ]);
        }

        return redirect()->route('employees.show', $employee->id)->with('success', 'Employee successfully registered!');
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'position',
            'lifecycleEvents',
            'leaveBalances.leaveType',
            'leaveApplications.leaveType',
            'attendanceRecords',
            'payslips'
        ]);

        $departments = Department::all();
        $positions = Position::all();

        return view('employees.show', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|string',
            'salary_grade' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'required|numeric|min:0',
            'transport_allowance' => 'required|numeric|min:0',
        ]);

        $oldStatus = $employee->status;
        $employee->update($validated);

        if ($oldStatus !== $employee->status) {
            LifecycleEvent::create([
                'employee_id' => $employee->id,
                'event_type' => 'Status Change',
                'effective_date' => Carbon::today()->toDateString(),
                'previous_value' => $oldStatus,
                'new_value' => $employee->status,
                'description' => "Employment status updated from {$oldStatus} to {$employee->status}.",
                'performed_by' => 'HR Administrator'
            ]);
        }

        return redirect()->back()->with('success', 'Employee profile updated successfully!');
    }
}
