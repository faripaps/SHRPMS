<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveApplication;
use App\Models\PayrollRun;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'Active')->count();
        $probationEmployees = Employee::where('status', 'Probation')->count();

        $today = Carbon::today()->toDateString();
        $employeesOnLeave = LeaveApplication::where('status', 'Approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();

        $latestPayroll = PayrollRun::where('status', 'Approved')->latest('id')->first();
        $monthlyPayrollCost = $latestPayroll ? (float) $latestPayroll->total_gross_pay : Employee::sum('basic_salary');

        // Turnover rate simulation: (Terminated + Resigned) / Total Employees * 100
        $inactive = Employee::whereIn('status', ['Resigned', 'Terminated', 'Retired'])->count();
        $turnoverRate = $totalEmployees > 0 ? round(($inactive / ($totalEmployees + $inactive)) * 100, 1) : 0.0;

        // Department breakdown for Donut Chart
        $departments = Department::withCount('employees')->get();
        $deptLabels = $departments->pluck('name')->toArray();
        $deptCounts = $departments->pluck('employees_count')->toArray();

        // Attendance stats for today
        $presentToday = AttendanceRecord::where('date', $today)->where('status', 'Present')->count();
        $lateToday = AttendanceRecord::where('date', $today)->where('status', 'Late')->count();

        // Recent Leave Applications
        $recentLeaves = LeaveApplication::with(['employee', 'leaveType'])
            ->latest('id')
            ->take(5)
            ->get();

        // Recent Employees
        $recentEmployees = Employee::with('department')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalEmployees',
            'activeEmployees',
            'probationEmployees',
            'employeesOnLeave',
            'monthlyPayrollCost',
            'turnoverRate',
            'deptLabels',
            'deptCounts',
            'presentToday',
            'lateToday',
            'recentLeaves',
            'recentEmployees'
        ));
    }
}
