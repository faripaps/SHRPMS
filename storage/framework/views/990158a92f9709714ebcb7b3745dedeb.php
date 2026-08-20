<?php $__env->startSection('content'); ?>
<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Employee Payslip Repository</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">View, export, and download official monthly employee payslip PDF documents.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="glass-panel" style="padding: 1.25rem;">
        <form action="<?php echo e(route('payslips.index')); ?>" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <div>
                <label class="form-label">Employee</label>
                <select name="employee_id" class="form-control">
                    <option value="">All Employees</option>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($emp->id); ?>" <?php echo e(request('employee_id') == $emp->id ? 'selected' : ''); ?>><?php echo e($emp->full_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="form-label">Month</label>
                <select name="month" class="form-control">
                    <option value="">All Months</option>
                    <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>><?php echo e(date('F', mktime(0, 0, 0, $m, 1))); ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Filter Payslips</button>
                <a href="<?php echo e(route('payslips.index')); ?>" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Payslip Grid / Table -->
    <div class="glass-panel" style="overflow: hidden;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Payslip Reference</th>
                    <th>Employee</th>
                    <th>Period</th>
                    <th>Gross Pay</th>
                    <th>Total Deductions</th>
                    <th>Net Pay</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payslips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight: 600; color: #818cf8;"><?php echo e($ps->payslip_number); ?></td>
                        <td>
                            <div style="font-weight: 700; color: #fff;"><?php echo e($ps->employee->full_name); ?></div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo e($ps->employee->department->name ?? ''); ?></div>
                        </td>
                        <td style="font-weight: 600; color: #fff;">
                            <?php echo e(date('F Y', mktime(0, 0, 0, $ps->payroll_month, 1, $ps->payroll_year))); ?>

                        </td>
                        <td>$<?php echo e(number_format($ps->gross_pay, 2)); ?></td>
                        <td style="color: #f87171;">-$<?php echo e(number_format($ps->total_deductions, 2)); ?></td>
                        <td>
                            <strong style="color: #34d399; font-size: 1.05rem;">$<?php echo e(number_format($ps->net_pay, 2)); ?></strong>
                        </td>
                        <td>
                            <span class="badge badge-present"><?php echo e($ps->payment_status); ?></span>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?php echo e(route('payslips.show', $ps->id)); ?>" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;">
                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                <span>View</span>
                            </a>
                            <a href="<?php echo e(route('payslips.pdf', $ps->id)); ?>" class="btn btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;">
                                <i data-lucide="download" style="width: 14px; height: 14px;"></i>
                                <span>PDF</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem;">No payslips found in repository.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border);">
            <?php echo e($payslips->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/payslips/index.blade.php ENDPATH**/ ?>