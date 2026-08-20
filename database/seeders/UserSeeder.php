<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $sarah = Employee::where('email', 'sarah.jenkins@company.com')->first();
        $marcus = Employee::where('email', 'marcus.vance@company.com')->first();
        $elena = Employee::where('email', 'elena.rostova@company.com')->first();

        // HR Admin User
        User::updateOrCreate(
            ['email' => 'admin@hrms.com'],
            [
                'name' => 'Sarah Jenkins (HR Admin)',
                'password' => Hash::make('password'),
                'role' => 'hr_admin',
                'employee_id' => $sarah?->id,
            ]
        );

        // Manager User
        User::updateOrCreate(
            ['email' => 'manager@hrms.com'],
            [
                'name' => 'Marcus Vance (Finance Head)',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'employee_id' => $marcus?->id,
            ]
        );

        // Employee User
        User::updateOrCreate(
            ['email' => 'employee@hrms.com'],
            [
                'name' => 'Elena Rostova (Senior Architect)',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'employee_id' => $elena?->id,
            ]
        );
    }
}
