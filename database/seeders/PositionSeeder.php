<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $hr = Department::where('code', 'HR')->first();
        $fin = Department::where('code', 'FIN')->first();
        $sal = Department::where('code', 'SAL')->first();
        $mkt = Department::where('code', 'MKT')->first();
        $ops = Department::where('code', 'OPS')->first();
        $ict = Department::where('code', 'ICT')->first();

        $positions = [
            // HR
            ['department_id' => $hr->id, 'title' => 'HR Director', 'code' => 'HR-DIR', 'salary_grade' => 'Executive Grade 5', 'min_salary' => 85000, 'max_salary' => 120000],
            ['department_id' => $hr->id, 'title' => 'Payroll & Benefits Manager', 'code' => 'HR-PAY', 'salary_grade' => 'Grade 4', 'min_salary' => 60000, 'max_salary' => 85000],
            ['department_id' => $hr->id, 'title' => 'Talent Acquisition Officer', 'code' => 'HR-REC', 'salary_grade' => 'Grade 2', 'min_salary' => 40000, 'max_salary' => 58000],

            // FIN
            ['department_id' => $fin->id, 'title' => 'Chief Financial Officer', 'code' => 'FIN-CFO', 'salary_grade' => 'Executive Grade 5', 'min_salary' => 95000, 'max_salary' => 140000],
            ['department_id' => $fin->id, 'title' => 'Senior Financial Controller', 'code' => 'FIN-CTL', 'salary_grade' => 'Grade 4', 'min_salary' => 65000, 'max_salary' => 90000],
            ['department_id' => $fin->id, 'title' => 'Staff Accountant', 'code' => 'FIN-ACC', 'salary_grade' => 'Grade 2', 'min_salary' => 42000, 'max_salary' => 60000],

            // SAL
            ['department_id' => $sal->id, 'title' => 'VP of Global Sales', 'code' => 'SAL-VP', 'salary_grade' => 'Executive Grade 5', 'min_salary' => 90000, 'max_salary' => 135000],
            ['department_id' => $sal->id, 'title' => 'Enterprise Account Executive', 'code' => 'SAL-AE', 'salary_grade' => 'Grade 3', 'min_salary' => 50000, 'max_salary' => 75000],

            // MKT
            ['department_id' => $mkt->id, 'title' => 'Marketing Communications Lead', 'code' => 'MKT-LEAD', 'salary_grade' => 'Grade 4', 'min_salary' => 58000, 'max_salary' => 80000],
            ['department_id' => $mkt->id, 'title' => 'Digital Content Specialist', 'code' => 'MKT-SPEC', 'salary_grade' => 'Grade 2', 'min_salary' => 38000, 'max_salary' => 52000],

            // OPS
            ['department_id' => $ops->id, 'title' => 'Head of Logistics & Supply Chain', 'code' => 'OPS-HEAD', 'salary_grade' => 'Grade 4', 'min_salary' => 62000, 'max_salary' => 88000],
            ['department_id' => $ops->id, 'title' => 'Facilities Supervisor', 'code' => 'OPS-SUP', 'salary_grade' => 'Grade 2', 'min_salary' => 36000, 'max_salary' => 50000],

            // ICT
            ['department_id' => $ict->id, 'title' => 'Chief Technology Officer', 'code' => 'ICT-CTO', 'salary_grade' => 'Executive Grade 5', 'min_salary' => 105000, 'max_salary' => 150000],
            ['department_id' => $ict->id, 'title' => 'Senior Software Architect', 'code' => 'ICT-ARCH', 'salary_grade' => 'Grade 4', 'min_salary' => 75000, 'max_salary' => 105000],
            ['department_id' => $ict->id, 'title' => 'DevOps & Systems Administrator', 'code' => 'ICT-OPS', 'salary_grade' => 'Grade 3', 'min_salary' => 55000, 'max_salary' => 78000],
        ];

        foreach ($positions as $pos) {
            Position::updateOrCreate(['code' => $pos['code']], $pos);
        }
    }
}
