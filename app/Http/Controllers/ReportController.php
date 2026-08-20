<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveApplication;
use App\Models\PayrollRun;
use App\Models\Payslip;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->input('tab', 'hr');

        // HR Data
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'Active')->count();
        $terminatedEmployees = Employee::whereIn('status', ['Resigned', 'Terminated', 'Retired'])->count();

        $employeesByDept = Department::withCount('employees')->get();
        $leavesSummary = LeaveApplication::with('leaveType')
            ->selectRaw('leave_type_id, count(*) as total_requests, sum(total_days) as total_days_taken')
            ->groupBy('leave_type_id')
            ->get();

        // Payroll Data
        $latestPayroll = PayrollRun::where('status', 'Approved')->latest('id')->first();
        $latestPayslips = $latestPayroll ? Payslip::where('payroll_run_id', $latestPayroll->id)->get() : collect();

        $totalBasic = $latestPayslips->sum('basic_salary');
        $totalAllowances = $latestPayslips->sum('housing_allowance') + $latestPayslips->sum('transport_allowance') + $latestPayslips->sum('overtime_pay') + $latestPayslips->sum('bonus');
        $totalTax = $latestPayslips->sum('income_tax');
        $totalPension = $latestPayslips->sum('pension');
        $totalSocialSecurity = $latestPayslips->sum('social_security');
        $totalMedicalAid = $latestPayslips->sum('medical_aid');
        $totalNetPay = $latestPayslips->sum('net_pay');

        return view('reports.index', compact(
            'activeTab',
            'totalEmployees',
            'activeEmployees',
            'terminatedEmployees',
            'employeesByDept',
            'leavesSummary',
            'latestPayroll',
            'latestPayslips',
            'totalBasic',
            'totalAllowances',
            'totalTax',
            'totalPension',
            'totalSocialSecurity',
            'totalMedicalAid',
            'totalNetPay'
        ));
    }
}
