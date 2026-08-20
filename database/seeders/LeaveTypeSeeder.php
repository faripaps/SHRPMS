<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Annual Leave', 'code' => 'ANNUAL', 'default_days_per_year' => 21, 'is_paid' => true, 'color_hex' => '#3b82f6'],
            ['name' => 'Sick Leave', 'code' => 'SICK', 'default_days_per_year' => 12, 'is_paid' => true, 'color_hex' => '#ef4444'],
            ['name' => 'Maternity Leave', 'code' => 'MATERNITY', 'default_days_per_year' => 90, 'is_paid' => true, 'color_hex' => '#ec4899'],
            ['name' => 'Paternity Leave', 'code' => 'PATERNITY', 'default_days_per_year' => 14, 'is_paid' => true, 'color_hex' => '#8b5cf6'],
            ['name' => 'Compassionate Leave', 'code' => 'COMPASSIONATE', 'default_days_per_year' => 5, 'is_paid' => true, 'color_hex' => '#f59e0b'],
            ['name' => 'Study Leave', 'code' => 'STUDY', 'default_days_per_year' => 7, 'is_paid' => true, 'color_hex' => '#10b981'],
            ['name' => 'Unpaid Leave', 'code' => 'UNPAID', 'default_days_per_year' => 30, 'is_paid' => false, 'color_hex' => '#6b7280'],
        ];

        foreach ($types as $t) {
            LeaveType::updateOrCreate(['code' => $t['code']], $t);
        }
    }
}
