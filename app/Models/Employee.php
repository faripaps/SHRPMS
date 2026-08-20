<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'national_id',
        'address',
        'phone',
        'email',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'department_id',
        'position_id',
        'date_hired',
        'employment_type',
        'status',
        'salary_grade',
        'basic_salary',
        'housing_allowance',
        'transport_allowance',
        'avatar_url'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_hired' => 'date',
        'basic_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function lifecycleEvents(): HasMany
    {
        return $this->hasMany(LifecycleEvent::class)->orderBy('effective_date', 'desc');
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveApplications(): HasMany
    {
        return $this->hasMany(LeaveApplication::class)->orderBy('created_at', 'desc');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class)->orderBy('date', 'desc');
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class)->orderBy('created_at', 'desc');
    }
}
