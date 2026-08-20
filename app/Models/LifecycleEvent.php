<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LifecycleEvent extends Model
{
    protected $fillable = [
        'employee_id',
        'event_type',
        'effective_date',
        'previous_value',
        'new_value',
        'description',
        'performed_by'
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
