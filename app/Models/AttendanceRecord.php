<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'work_hours',
        'overtime_hours',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'work_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
