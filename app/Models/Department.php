<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'head_of_department_id',
        'branch',
        'budget'
    ];

    public function employees(): HasMany
    {
        return $table_rel = $this->hasMany(Employee::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function headOfDepartment(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'head_of_department_id');
    }
}
