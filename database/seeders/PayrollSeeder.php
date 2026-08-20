<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Services\PayrollEngineService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $engine = new PayrollEngineService();
        $employees = Employee::all();

        $month = 7; // July 2026
        $year = 2026;
        $batchRef = "PAY-2026-07";

        $payrollRun = PayrollRun::updateOrCreate(
            ['batch_reference' => $batchRef],
            [
                'payroll_month' => $month,
                'payroll_year' => $year,
                'total_employees' => $employees->count(),
                'status' => 'Approved',
                'processed_by' => 'HR Administrator',
                'processed_at' => Carbon::now()->subDays(15),
            ]
        );

        $totBasic = 0.0;
        $totAllowances = 0.0;
        $totGross = 0.0;
        $totDeductions = 0.0;
        $totNet = 0.0;

        foreach ($employees as $index => $emp) {
            $bonus = ($index === 0) ? 5000.00 : 0.00;
            $calc = $engine->calculateEmployeePayroll($emp, $month, $year, $bonus);

            $payslipNum = sprintf("PSL-2026-07-%03d", $emp->id);

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
    }
}
