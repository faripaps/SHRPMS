<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRun extends Model
{
    protected $fillable = [
        'batch_reference',
        'payroll_month',
        'payroll_year',
        'total_employees',
        'total_basic_salary',
        'total_allowances',
        'total_gross_pay',
        'total_deductions',
        'total_net_pay',
        'status',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'total_basic_salary' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net_pay' => 'decimal:2',
    ];

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }
}
