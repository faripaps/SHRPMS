<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lifecycle_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('event_type'); // Onboarding, Confirmation, Promotion, Transfer, Salary Adjustment, Contract Renewal, Resignation, Retirement, Termination
            $table->date('effective_date');
            $table->text('previous_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('description')->nullable();
            $table->string('performed_by')->default('HR Administrator');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lifecycle_events');
    }
};
