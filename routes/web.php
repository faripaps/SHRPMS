<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LifecycleController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleSwitcherController;
use App\Http\Middleware\CheckRole;

// Role Switcher Route
Route::post('/switch-role', [RoleSwitcherController::class, 'switchRole'])->name('switch-role');

// Main Application Routes
Route::middleware([CheckRole::class . ':*'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Employee Management Module
    Route::resource('employees', EmployeeController::class);

    // 2. Employment Lifecycle Module
    Route::get('/lifecycle', [LifecycleController::class, 'index'])->name('lifecycle.index');
    Route::post('/lifecycle', [LifecycleController::class, 'store'])->name('lifecycle.store');

    // 3. Leave Management Module
    Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::patch('/leave/{application}/status', [LeaveController::class, 'updateStatus'])->name('leave.updateStatus');

    // 4. Attendance & Time Management Module
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // 5. Payroll Management Module
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll/process', [PayrollController::class, 'process'])->name('payroll.process');
    Route::get('/payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');

    // 6. Payslip Generation Module
    Route::get('/payslips', [PayslipController::class, 'index'])->name('payslips.index');
    Route::get('/payslips/{payslip}', [PayslipController::class, 'show'])->name('payslips.show');
    Route::get('/payslips/{payslip}/pdf', [PayslipController::class, 'downloadPdf'])->name('payslips.pdf');

    // 7. Department & Organizational Structure Module
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');

    // 9. Reports & Analytics Module
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
