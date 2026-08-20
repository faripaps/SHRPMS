<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->default('Female');
            $table->date('date_of_birth');
            $table->string('national_id')->unique();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('set null');
            
            $table->date('date_hired');
            $table->string('employment_type')->default('Full-Time'); // Full-Time, Contract, Probation
            $table->string('status')->default('Active'); // Active, Probation, Contract, Suspended, Resigned, Retired, Terminated
            $table->string('salary_grade')->default('Grade 2');
            
            $table->decimal('basic_salary', 12, 2)->default(45000.00);
            $table->decimal('housing_allowance', 12, 2)->default(8000.00);
            $table->decimal('transport_allowance', 12, 2)->default(5000.00);
            $table->string('avatar_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
