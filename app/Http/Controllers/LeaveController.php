<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $applications = LeaveApplication::with(['employee.department', 'leaveType'])
            ->latest('id')
            ->paginate(10);

        $pendingCount = LeaveApplication::where('status', 'Pending')->count();
        $approvedCount = LeaveApplication::where('status', 'Approved')->count();
        $rejectedCount = LeaveApplication::where('status', 'Rejected')->count();

        $employees = Employee::where('status', 'Active')->get();
        $leaveTypes = LeaveType::all();

        return view('leave.index', compact(
            'applications',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'employees',
            'leaveTypes'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $totalDays = $start->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $end) + 1;

        // Check balance
        $balance = LeaveBalance::where('employee_id', $request->employee_id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', Carbon::now()->year)
            ->first();

        if ($balance && ($balance->total_entitled - $balance->used_days - $balance->pending_days) < $totalDays) {
            return redirect()->back()->with('error', "Insufficient leave balance. Remaining entitlement is " . ($balance->total_entitled - $balance->used_days - $balance->pending_days) . " days.");
        }

        $application = LeaveApplication::create([
            'employee_id' => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'Pending'
        ]);

        if ($balance) {
            $balance->increment('pending_days', $totalDays);
        }

        return redirect()->back()->with('success', "Leave application submitted for {$totalDays} days!");
    }

    public function updateStatus(Request $request, LeaveApplication $application)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'rejection_reason' => 'nullable|string'
        ]);

        $balance = LeaveBalance::where('employee_id', $application->employee_id)
            ->where('leave_type_id', $application->leave_type_id)
            ->where('year', Carbon::now()->year)
            ->first();

        if ($application->status === 'Pending') {
            if ($balance) {
                $balance->decrement('pending_days', $application->total_days);
            }

            if ($request->status === 'Approved') {
                if ($balance) {
                    $balance->increment('used_days', $application->total_days);
                }
                $application->update([
                    'status' => 'Approved',
                    'approved_by' => 'HR Administrator / Line Manager'
                ]);
            } else {
                $application->update([
                    'status' => 'Rejected',
                    'rejection_reason' => $request->rejection_reason ?? 'Operational requirements.',
                    'approved_by' => 'HR Administrator / Line Manager'
                ]);
            }
        }

        return redirect()->back()->with('success', "Leave request status updated to {$request->status}!");
    }
}
