<?php

namespace App\Http\Controllers;

use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\Employee;
use App\Services\PayrollEngineService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index()
    {
        $runs = PayrollRun::latest('id')->paginate(10);
        $employeesCount = Employee::where('status', 'Active')->count();

        return view('payroll.index', compact('runs', 'employeesCount'));
    }

    public function process(Request $request, PayrollEngineService $engine)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $month = $request->month;
        $year = $request->year;
        $batchRef = sprintf("PAY-%d-%02d", $year, $month);

        // Prevent duplicate run if locked
        $existing = PayrollRun::where('batch_reference', $batchRef)->first();
        if ($existing && $existing->status === 'Locked') {
            return redirect()->back()->with('error', "Payroll batch {$batchRef} is locked and cannot be re-processed.");
        }

        $employees = Employee::whereIn('status', ['Active', 'Probation', 'Contract'])->get();

        $payrollRun = PayrollRun::updateOrCreate(
            ['batch_reference' => $batchRef],
            [
                'payroll_month' => $month,
                'payroll_year' => $year,
                'total_employees' => $employees->count(),
                'status' => 'Approved',
                'processed_by' => 'HR Administrator',
                'processed_at' => Carbon::now(),
            ]
        );

        $totBasic = 0.0;
        $totAllowances = 0.0;
        $totGross = 0.0;
        $totDeductions = 0.0;
        $totNet = 0.0;

        foreach ($employees as $emp) {
            $calc = $engine->calculateEmployeePayroll($emp, $month, $year);

            $payslipNum = sprintf("PSL-%d-%02d-%03d", $year, $month, $emp->id);

            Payslip::updateOrCreate(
                ['payslip_number' => $payslipNum],
                [
                    'payroll_run_id' => $payrollRun->id,
                    'employee_id' => $emp->id,
                    'payroll_month' => $month,
                    'payroll_year' => $year,
                    'basic_salary' => $calc['basic_salary'],
                    'housing_allowance' => $calc['housing_allowance'],
                    'transport_allowance' => $calc['transport_allowance'],
                    'overtime_pay' => $calc['overtime_pay'],
                    'bonus' => $calc['bonus'],
                    'commission' => $calc['commission'],
                    'gross_pay' => $calc['gross_pay'],
                    'income_tax' => $calc['income_tax'],
                    'pension' => $calc['pension'],
                    'social_security' => $calc['social_security'],
                    'medical_aid' => $calc['medical_aid'],
                    'absence_deduction' => $calc['absence_deduction'],
                    'loan_deduction' => $calc['loan_deduction'],
                    'total_deductions' => $calc['total_deductions'],
                    'net_pay' => $calc['net_pay'],
                    'payment_status' => 'Paid',
                ]
            );

            $totBasic += $calc['basic_salary'];
            $totAllowances += ($calc['housing_allowance'] + $calc['transport_allowance'] + $calc['overtime_pay'] + $calc['bonus']);
            $totGross += $calc['gross_pay'];
            $totDeductions += $calc['total_deductions'];
            $totNet += $calc['net_pay'];
        }

        $payrollRun->update([
            'total_basic_salary' => $totBasic,
            'total_allowances' => $totAllowances,
            'total_gross_pay' => $totGross,
            'total_deductions' => $totDeductions,
            'total_net_pay' => $totNet,
        ]);

        return redirect()->route('payroll.show', $payrollRun->id)->with('success', "Payroll batch {$batchRef} successfully processed for {$employees->count()} employees!");
    }

    public function show(PayrollRun $payroll)
    {
        $payroll->load('payslips.employee.department');
        return view('payroll.show', compact('payroll'));
    }
}
