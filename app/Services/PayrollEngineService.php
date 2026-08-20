<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\AttendanceRecord;

class PayrollEngineService
{
    /**
     * Calculate single employee's monthly payroll line item
     */
    public function calculateEmployeePayroll(Employee $employee, int $month, int $year, float $bonus = 0.0, float $commission = 0.0): array
    {
        $basicSalary = (float) $employee->basic_salary;
        $housingAllowance = (float) $employee->housing_allowance;
        $transportAllowance = (float) $employee->transport_allowance;

        // Calculate Overtime and Absences from attendance records
        $attendanceRecords = AttendanceRecord::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $overtimeHours = $attendanceRecords->sum('overtime_hours');
        $absentDays = $attendanceRecords->where('status', 'Absent')->count();

        // Hourly rate assumes 160 working hours per month (20 days * 8 hrs)
        $hourlyRate = $basicSalary / 160;
        $dailyRate = $basicSalary / 20;

        $overtimePay = round($overtimeHours * $hourlyRate * 1.5, 2);
        $absenceDeduction = round($absentDays * $dailyRate, 2);

        $grossPay = round($basicSalary + $housingAllowance + $transportAllowance + $overtimePay + $bonus + $commission, 2);

        // Deductions
        $incomeTax = $this->calculateIncomeTax($grossPay);
        $pension = round($basicSalary * 0.08, 2); // 8% Pension
        $socialSecurity = round($basicSalary * 0.05, 2); // 5% Social Security
        $medicalAid = round($basicSalary * 0.03, 2); // 3% Medical Aid
        $loanDeduction = 0.00;

        $totalDeductions = round($incomeTax + $pension + $socialSecurity + $medicalAid + $absenceDeduction + $loanDeduction, 2);
        $netPay = round($grossPay - $totalDeductions, 2);

        return [
            'basic_salary' => $basicSalary,
            'housing_allowance' => $housingAllowance,
            'transport_allowance' => $transportAllowance,
            'overtime_pay' => $overtimePay,
            'bonus' => $bonus,
            'commission' => $commission,
            'gross_pay' => $grossPay,
            'income_tax' => $incomeTax,
            'pension' => $pension,
            'social_security' => $socialSecurity,
            'medical_aid' => $medicalAid,
            'absence_deduction' => $absenceDeduction,
            'loan_deduction' => $loanDeduction,
            'total_deductions' => $totalDeductions,
            'net_pay' => $netPay,
        ];
    }

    /**
     * Progressive Income Tax Bracket Calculation
     */
    private function calculateIncomeTax(float $grossPay): float
    {
        $taxable = max(0, $grossPay - 5000); // $5,000 tax-free threshold
        $tax = 0.0;

        if ($taxable <= 15000) {
            $tax = $taxable * 0.10;
        } elseif ($taxable <= 40000) {
            $tax = (15000 * 0.10) + (($taxable - 15000) * 0.18);
        } else {
            $tax = (15000 * 0.10) + (25000 * 0.18) + (($taxable - 40000) * 0.25);
        }

        return round($tax, 2);
    }
}
