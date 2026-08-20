<?php $__env->startSection('content'); ?>
<div style="display: flex; flex-direction: column; gap: 1.5rem;" x-data="{ showPayrollModal: false }">
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Payroll Processing Engine</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Automate monthly salary computation, allowances, tax deductions, pension, and net pay.</p>
        </div>

        <button @click="showPayrollModal = true" class="btn btn-primary">
            <i data-lucide="calculator" style="width: 18px; height: 18px;"></i>
            <span>Execute Monthly Payroll Batch</span>
        </button>
    </div>

    <!-- Process Payroll Modal -->
    <div x-show="showPayrollModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 1rem;" x-cloak>
        <div class="glass-panel" style="width: 100%; max-width: 500px; padding: 2rem;" @click.away="showPayrollModal = false">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Run Monthly Payroll Calculation</h2>
                <button @click="showPayrollModal = false" style="background: none; border: none; color: var(--text-muted); cursor: pointer;"><i data-lucide="x"></i></button>
            </div>

            <form action="<?php echo e(route('payroll.process')); ?>" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                <?php echo csrf_field(); ?>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Payroll Month *</label>
                        <select name="month" class="form-control" required>
                            <?php for($m = 1; $m <= 12; $m++): ?>
                                <option value="<?php echo e($m); ?>" <?php echo e($m == date('n') ? 'selected' : ''); ?>>
                                    <?php echo e(date('F', mktime(0, 0, 0, $m, 1))); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payroll Year *</label>
                        <select name="year" class="form-control" required>
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                </div>

                <div style="padding: 1rem; background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: var(--radius-sm); font-size: 0.85rem; color: #818cf8;">
                    <strong>Automated Calculations Included:</strong>
                    <ul style="margin-left: 1.25rem; margin-top: 0.35rem; line-height: 1.4;">
                        <li>Basic Salary + Housing & Transport Allowances</li>
                        <li>Overtime Pay (1.5x rate based on attendance logs)</li>
                        <li>Progressive Income Tax Brackets</li>
                        <li>8% Pension & 5% Social Security Deductions</li>
                    </ul>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
                    <button type="button" @click="showPayrollModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Process Batch (<?php echo e($employeesCount); ?> Staff)</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Runs Table -->
    <div class="glass-panel" style="overflow: hidden;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Batch Ref</th>
                    <th>Payroll Period</th>
                    <th>Staff Count</th>
                    <th>Gross Pay</th>
                    <th>Total Deductions</th>
                    <th>Total Net Pay</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $runs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $run): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong style="color: #818cf8; font-size: 0.95rem;"><?php echo e($run->batch_reference); ?></strong>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">By: <?php echo e($run->processed_by); ?></div>
                        </td>
                        <td style="font-weight: 600; color: #fff;">
                            <?php echo e(date('F Y', mktime(0, 0, 0, $run->payroll_month, 1, $run->payroll_year))); ?>

                        </td>
                        <td>
                            <strong style="color: #fff;"><?php echo e($run->total_employees); ?> Employees</strong>
                        </td>
                        <td>
                            <span style="color: #fff; font-weight: 600;">$<?php echo e(number_format($run->total_gross_pay, 2)); ?></span>
                        </td>
                        <td>
                            <span style="color: #f87171; font-weight: 600;">-$<?php echo e(number_format($run->total_deductions, 2)); ?></span>
                        </td>
                        <td>
                            <strong style="color: #34d399; font-size: 1.05rem;">$<?php echo e(number_format($run->total_net_pay, 2)); ?></strong>
                        </td>
                        <td>
                            <span class="badge badge-approved"><?php echo e($run->status); ?></span>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?php echo e(route('payroll.show', $run->id)); ?>" class="btn btn-secondary" style="padding: 0.4rem 0.85rem; font-size: 0.8rem;">
                                <i data-lucide="file-spreadsheet" style="width: 14px; height: 14px;"></i>
                                <span>Register</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem;">No payroll batches executed yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border);">
            <?php echo e($runs->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/payroll/index.blade.php ENDPATH**/ ?>