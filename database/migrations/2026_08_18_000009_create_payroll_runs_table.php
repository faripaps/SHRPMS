<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->string('batch_reference')->unique();
            $table->integer('payroll_month');
            $table->integer('payroll_year');
            $table->integer('total_employees')->default(0);
            $table->decimal('total_basic_salary', 15, 2)->default(0.00);
            $table->decimal('total_allowances', 15, 2)->default(0.00);
            $table->decimal('total_gross_pay', 15, 2)->default(0.00);
            $table->decimal('total_deductions', 15, 2)->default(0.00);
            $table->decimal('total_net_pay', 15, 2)->default(0.00);
            $table->string('status')->default('Processed'); // Draft, Processed, Approved, Locked
            $table->string('processed_by')->default('HR Administrator');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};
