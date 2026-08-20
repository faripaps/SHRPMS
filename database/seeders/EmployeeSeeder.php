<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\LifecycleEvent;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $hr = Department::where('code', 'HR')->first();
        $fin = Department::where('code', 'FIN')->first();
        $sal = Department::where('code', 'SAL')->first();
        $mkt = Department::where('code', 'MKT')->first();
        $ops = Department::where('code', 'OPS')->first();
        $ict = Department::where('code', 'ICT')->first();

        $hrDir = Position::where('code', 'HR-DIR')->first();
        $hrPay = Position::where('code', 'HR-PAY')->first();
        $finCfo = Position::where('code', 'FIN-CFO')->first();
        $finCtl = Position::where('code', 'FIN-CTL')->first();
        $salVp = Position::where('code', 'SAL-VP')->first();
        $salAe = Position::where('code', 'SAL-AE')->first();
        $mktLead = Position::where('code', 'MKT-LEAD')->first();
        $opsHead = Position::where('code', 'OPS-HEAD')->first();
        $ictCto = Position::where('code', 'ICT-CTO')->first();
        $ictArch = Position::where('code', 'ICT-ARCH')->first();
        $ictOps = Position::where('code', 'ICT-OPS')->first();

        $employeesData = [
            [
                'employee_number' => 'EMP-2024-001',
                'first_name' => 'Sarah',
                'last_name' => 'Jenkins',
                'gender' => 'Female',
                'date_of_birth' => '1985-04-12',
                'national_id' => 'NID-850412-001',
                'address' => '742 Evergreen Terrace, Springfield',
                'phone' => '+1 (555) 234-5678',
                'email' => 'sarah.jenkins@company.com',
                'emergency_contact_name' => 'David Jenkins',
                'emergency_contact_phone' => '+1 (555) 987-6543',
                'emergency_contact_relationship' => 'Spouse',
                'department_id' => $hr->id,
                'position_id' => $hrDir->id,
                'date_hired' => '2020-01-15',
                'employment_type' => 'Full-Time',
                'status' => 'Active',
                'salary_grade' => 'Executive Grade 5',
                'basic_salary' => 95000.00,
                'housing_allowance' => 15000.00,
                'transport_allowance' => 8000.00,
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80'
            ],
            [
                'employee_number' => 'EMP-2024-002',
                'first_name' => 'Marcus',
                'last_name' => 'Vance',
                'gender' => 'Male',
                'date_of_birth' => '1988-09-23',
                'national_id' => 'NID-880923-002',
                'address' => '1204 Wall Street Suite 400',
                'phone' => '+1 (555) 345-6789',
                'email' => 'marcus.vance@company.com',
                'emergency_contact_name' => 'Elena Vance',
                'emergency_contact_phone' => '+1 (555) 876-5432',
                'emergency_contact_relationship' => 'Sister',
                'department_id' => $fin->id,
                'position_id' => $finCfo->id,
                'date_hired' => '2019-06-01',
                'employment_type' => 'Full-Time',
                'status' => 'Active',
                'salary_grade' => 'Executive Grade 5',
                'basic_salary' => 110000.00,
                'housing_allowance' => 18000.00,
                'transport_allowance' => 10000.00,
                'avatar_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=150&auto=format&fit=crop&q=80'
            ],
            [
                'employee_number' => 'EMP-2025-003',
                'first_name' => 'Alex',
                'last_name' => 'Rivera',
                'gender' => 'Male',
                'date_of_birth' => '1992-11-05',
                'national_id' => 'NID-921105-003',
                'address' => '45 Tech Hub Way, Suite 300',
                'phone' => '+1 (555) 456-7890',
                'email' => 'alex.rivera@company.com',
                'emergency_contact_name' => 'Maria Rivera',
                'emergency_contact_phone' => '+1 (555) 765-4321',
                'emergency_contact_relationship' => 'Mother',
                'department_id' => $ict->id,
                'position_id' => $ictCto->id,
                'date_hired' => '2021-03-10',
                'employment_type' => 'Full-Time',
                'status' => 'Active',
                'salary_grade' => 'Executive Grade 5',
                'basic_salary' => 125000.00,
                'housing_allowance' => 20000.00,
                'transport_allowance' => 12000.00,
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80'
            ],
            [
                'employee_number' => 'EMP-2025-004',
                'first_name' => 'Elena',
                'last_name' => 'Rostova',
                'gender' => 'Female',
                'date_of_birth' => '1994-02-18',
                'national_id' => 'NID-940218-004',
                'address' => '88 Silicon Boulevard, Tech District',
                'phone' => '+1 (555) 567-8901',
                'email' => 'elena.rostova@company.com',
                'emergency_contact_name' => 'Dmitri Rostov',
                'emergency_contact_phone' => '+1 (555) 654-3210',
                'emergency_contact_relationship' => 'Brother',
                'department_id' => $ict->id,
                'position_id' => $ictArch->id,
                'date_hired' => '2022-08-01',
                'employment_type' => 'Full-Time',
                'status' => 'Active',
                'salary_grade' => 'Grade 4',
                'basic_salary' => 88000.00,
                'housing_allowance' => 12000.00,
                'transport_allowance' => 7000.00,
                'avatar_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150&auto=format&fit=crop&q=80'
            ],
            [
                'employee_number' => 'EMP-2025-005',
                'first_name' => 'David',
                'last_name' => 'O\'Connor',
                'gender' => 'Male',
                'date_of_birth' => '1990-07-30',
                'national_id' => 'NID-900730-005',
                'address' => '15 Commercial Plaza Drive',
                'phone' => '+1 (555) 678-9012',
                'email' => 'david.oconnor@company.com',
                'emergency_contact_name' => 'Claire O\'Connor',
                'emergency_contact_phone' => '+1 (555) 543-2109',
                'emergency_contact_relationship' => 'Spouse',
                'department_id' => $sal->id,
                'position_id' => $salVp->id,
                'date_hired' => '2021-11-15',
                'employment_type' => 'Full-Time',
                'status' => 'Active',
                'salary_grade' => 'Executive Grade 5',
                'basic_salary' => 98000.00,
                'housing_allowance' => 16000.00,
                'transport_allowance' => 9000.00,
                'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80'
            ],
            [
                'employee_number' => 'EMP-2026-006',
                'first_name' => 'Priya',
                'last_name' => 'Sharma',
                'gender' => 'Female',
                'date_of_birth' => '1996-12-14',
                'national_id' => 'NID-961214-006',
                'address' => '304 Innovation Park Way',
                'phone' => '+1 (555) 789-0123',
                'email' => 'priya.sharma@company.com',
                'emergency_contact_name' => 'Rajesh Sharma',
                'emergency_contact_phone' => '+1 (555) 432-1098',
                'emergency_contact_relationship' => 'Father',
                'department_id' => $mkt->id,
                'position_id' => $mktLead->id,
                'date_hired' => '2023-04-01',
                'employment_type' => 'Full-Time',
                'status' => 'Active',
                'salary_grade' => 'Grade 4',
                'basic_salary' => 72000.00,
                'housing_allowance' => 10000.00,
                'transport_allowance' => 6000.00,
                'avatar_url' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=150&auto=format&fit=crop&q=80'
            ]
        ];

        $leaveTypes = LeaveType::all();

        foreach ($employeesData as $empData) {
            $employee = Employee::updateOrCreate(['employee_number' => $empData['employee_number']], $empData);

            // Set Department Head if applicable
            if ($employee->position_id === $hrDir->id) $hr->update(['head_of_department_id' => $employee->id]);
            if ($employee->position_id === $finCfo->id) $fin->update(['head_of_department_id' => $employee->id]);
            if ($employee->position_id === $salVp->id) $sal->update(['head_of_department_id' => $employee->id]);
            if ($employee->position_id === $mktLead->id) $mkt->update(['head_of_department_id' => $employee->id]);
            if ($employee->position_id === $ictCto->id) $ict->update(['head_of_department_id' => $employee->id]);

            // Lifecycle event
            LifecycleEvent::create([
                'employee_id' => $employee->id,
                'event_type' => 'Onboarding',
                'effective_date' => $employee->date_hired,
                'new_value' => json_encode(['position' => $employee->position->title, 'salary' => $employee->basic_salary]),
                'description' => 'Employee successfully onboarded and verified by HR Administration.',
                'performed_by' => 'HR Administrator'
            ]);

            // Leave Balances
            foreach ($leaveTypes as $lt) {
                LeaveBalance::updateOrCreate(
                    ['employee_id' => $employee->id, 'leave_type_id' => $lt->id, 'year' => 2026],
                    ['total_entitled' => $lt->default_days_per_year, 'used_days' => rand(0, 4), 'pending_days' => 0]
                );
            }
        }
    }
}
