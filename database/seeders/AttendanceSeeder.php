<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $startDate = Carbon::now()->subDays(20);

        foreach ($employees as $emp) {
            for ($i = 0; $i < 20; $i++) {
                $currentDate = (clone $startDate)->addDays($i);

                // Skip weekends
                if ($currentDate->isWeekend()) {
                    continue;
                }

                $rand = rand(1, 100);
                $status = 'Present';
                $clockIn = '08:30:00';
                $clockOut = '17:30:00';
                $workHours = 8.0;
                $overtimeHours = 0.0;
                $notes = null;

                if ($rand <= 70) {
                    // Standard Present
                    $status = 'Present';
                } elseif ($rand <= 85) {
                    // Overtime
                    $status = 'Overtime';
                    $overtimeHours = rand(2, 4);
                    $clockOut = '20:30:00';
                    $notes = 'Approved project deadline overtime extension';
                } elseif ($rand <= 95) {
                    // Late
                    $status = 'Late';
                    $clockIn = '09:45:00';
                    $workHours = 7.0;
                    $notes = 'Traffic delay on highway';
                } else {
                    // On Leave
                    $status = 'On Leave';
                    $clockIn = null;
                    $clockOut = null;
                    $workHours = 0.0;
                    $notes = 'Approved Annual Leave';
                }

                AttendanceRecord::updateOrCreate(
                    ['employee_id' => $emp->id, 'date' => $currentDate->toDateString()],
                    [
                        'clock_in' => $clockIn,
                        'clock_out' => $clockOut,
                        'work_hours' => $workHours,
                        'overtime_hours' => $overtimeHours,
                        'status' => $status,
                        'notes' => $notes
                    ]
                );
            }
        }
    }
}
