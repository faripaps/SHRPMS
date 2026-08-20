<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    public function index(Request $request)
    {
        $query = Payslip::with(['employee.department', 'employee.position', 'payrollRun']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('month')) {
            $query->where('payroll_month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('payroll_year', $request->year);
        }

        $payslips = $query->latest('id')->paginate(15);
        $employees = Employee::all();

        return view('payslips.index', compact('payslips', 'employees'));
    }

    public function show(Payslip $payslip)
    {
        $payslip->load(['employee.department', 'employee.position', 'payrollRun']);
        return view('payslips.show', compact('payslip'));
    }

    public function downloadPdf(Payslip $payslip)
    {
        $payslip->load(['employee.department', 'employee.position', 'payrollRun']);

        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = Pdf::loadView('payslips.pdf', compact('payslip'));
            return $pdf->download("Payslip_{$payslip->payslip_number}_{$payslip->employee->first_name}_{$payslip->employee->last_name}.pdf");
        }

        // Fallback: render print-ready HTML view
        return view('payslips.pdf', compact('payslip'));
    }
}
