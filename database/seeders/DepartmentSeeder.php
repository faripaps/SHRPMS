<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Workforce administration, recruitment, compliance, and talent management.',
                'branch' => 'Headquarters Main Campus',
                'budget' => 650000.00
            ],
            [
                'name' => 'Finance & Accounting',
                'code' => 'FIN',
                'description' => 'Financial planning, payroll disbursement, audit, and budget control.',
                'branch' => 'Headquarters Main Campus',
                'budget' => 950000.00
            ],
            [
                'name' => 'Sales & Business Dev',
                'code' => 'SAL',
                'description' => 'Revenue generation, enterprise client relations, and commercial growth.',
                'branch' => 'Commercial Hub - North Tower',
                'budget' => 1200000.00
            ],
            [
                'name' => 'Marketing & Communications',
                'code' => 'MKT',
                'description' => 'Brand strategy, digital media, public relations, and campaign management.',
                'branch' => 'Commercial Hub - North Tower',
                'budget' => 480000.00
            ],
            [
                'name' => 'Operations & Supply',
                'code' => 'OPS',
                'description' => 'Logistics, facilities, inventory, and operational efficiency.',
                'branch' => 'Operations Logistics Hub',
                'budget' => 1100000.00
            ],
            [
                'name' => 'ICT & Infrastructure',
                'code' => 'ICT',
                'description' => 'Software engineering, cloud infrastructure, network security, and IT support.',
                'branch' => 'Tech Innovation Complex',
                'budget' => 1400000.00
            ]
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
