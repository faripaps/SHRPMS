<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'payslip_number',
        'payroll_month',
        'payroll_year',
        'basic_salary',
        'housing_allowance',
        'transport_allowance',
        'overtime_pay',
        'bonus',
        'commission',
        'gross_pay',
        'income_tax',
        'pension',
        'social_security',
        'medical_aid',
        'loan_deduction',
        'absence_deduction',
        'total_deductions',
        'net_pay',
        'payment_status'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonus' => 'decimal:2',
        'commission' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'income_tax' => 'decimal:2',
        'pension' => 'decimal:2',
        'social_security' => 'decimal:2',
        'medical_aid' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'absence_deduction' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
