<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained('payroll_runs')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('payslip_number')->unique();
            $table->integer('payroll_month');
            $table->integer('payroll_year');
            
            // Earnings
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('housing_allowance', 12, 2)->default(0.00);
            $table->decimal('transport_allowance', 12, 2)->default(0.00);
            $table->decimal('overtime_pay', 12, 2)->default(0.00);
            $table->decimal('bonus', 12, 2)->default(0.00);
            $table->decimal('commission', 12, 2)->default(0.00);
            $table->decimal('gross_pay', 12, 2);
            
            // Deductions
            $table->decimal('income_tax', 12, 2)->default(0.00);
            $table->decimal('pension', 12, 2)->default(0.00);
            $table->decimal('social_security', 12, 2)->default(0.00);
            $table->decimal('medical_aid', 12, 2)->default(0.00);
            $table->decimal('loan_deduction', 12, 2)->default(0.00);
            $table->decimal('absence_deduction', 12, 2)->default(0.00);
            $table->decimal('total_deductions', 12, 2);
            
            // Net
            $table->decimal('net_pay', 12, 2);
            $table->string('payment_status')->default('Paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
