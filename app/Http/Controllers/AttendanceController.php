<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        $records = AttendanceRecord::with('employee.department')
            ->where('date', $selectedDate)
            ->paginate(15);

        $employees = Employee::where('status', 'Active')->get();

        $presentCount = AttendanceRecord::where('date', $selectedDate)->where('status', 'Present')->count();
        $lateCount = AttendanceRecord::where('date', $selectedDate)->where('status', 'Late')->count();
        $overtimeCount = AttendanceRecord::where('date', $selectedDate)->where('status', 'Overtime')->count();
        $absentCount = AttendanceRecord::where('date', $selectedDate)->where('status', 'Absent')->count();

        return view('attendance.index', compact(
            'records',
            'employees',
            'selectedDate',
            'presentCount',
            'lateCount',
            'overtimeCount',
            'absentCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|string',
            'clock_out' => 'nullable|string',
            'overtime_hours' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $clockIn = $request->clock_in ? Carbon::parse($request->clock_in) : null;
        $clockOut = $request->clock_out ? Carbon::parse($request->clock_out) : null;
        $workHours = 8.00;

        if ($clockIn && $clockOut) {
            $workHours = round($clockIn->diffInMinutes($clockOut) / 60, 2);
        }

        AttendanceRecord::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $request->date],
            [
                'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'work_hours' => $workHours,
                'overtime_hours' => $request->overtime_hours ?? 0.00,
                'status' => $request->status,
                'notes' => $request->notes
            ]
        );

        return redirect()->back()->with('success', 'Attendance record updated successfully!');
    }
}
