<?php $__env->startSection('content'); ?>
<div style="display: flex; flex-direction: column; gap: 1.5rem;" x-data="{ showDeptModal: false }">
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Department & Organizational Structure</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Manage organizational hierarchy, department branches, heads of department, and position salary grade scales.</p>
        </div>

        <button @click="showDeptModal = true" class="btn btn-primary">
            <i data-lucide="plus-circle" style="width: 18px; height: 18px;"></i>
            <span>Add New Department</span>
        </button>
    </div>

    <!-- Department Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="glass-panel" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                        <span class="badge" style="background: rgba(99, 102, 241, 0.2); color: #818cf8; font-size: 0.8rem;"><?php echo e($dept->code); ?></span>
                        <span style="font-size: 0.8rem; color: var(--text-muted);"><?php echo e($dept->branch); ?></span>
                    </div>

                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: 0.35rem;"><?php echo e($dept->name); ?></h3>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin-bottom: 1rem;"><?php echo e($dept->description); ?></p>

                    <div style="background: rgba(15, 23, 42, 0.5); padding: 0.85rem; border-radius: var(--radius-sm); margin-bottom: 1rem; border: 1px solid var(--bg-card-border); font-size: 0.85rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="color: var(--text-muted);">Head of Dept:</span>
                            <strong style="color: #fff;"><?php echo e($dept->headOfDepartment->full_name ?? 'Unassigned'); ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Staff Members:</span>
                            <strong style="color: #34d399;"><?php echo e($dept->employees->count()); ?> Employees</strong>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--bg-card-border); padding-top: 0.85rem;">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Monthly Budget Allocation:</span>
                    <strong style="color: #fbbf24; font-size: 1.05rem;">$<?php echo e(number_format($dept->budget, 2)); ?></strong>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Position Salary Grade Matrix Table -->
    <div class="glass-panel" style="padding: 1.5rem;">
        <h2 style="font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: 1rem;">Position & Salary Grade Matrix</h2>
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Position Code</th>
                        <th>Position Title</th>
                        <th>Department</th>
                        <th>Salary Grade</th>
                        <th>Min Salary</th>
                        <th>Max Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="font-weight: 600; color: #818cf8;"><?php echo e($pos->code); ?></td>
                            <td style="font-weight: 700; color: #fff;"><?php echo e($pos->title); ?></td>
                            <td><?php echo e($pos->department->name ?? 'N/A'); ?></td>
                            <td><span class="badge badge-overtime"><?php echo e($pos->salary_grade); ?></span></td>
                            <td style="color: #34d399;">$<?php echo e(number_format($pos->min_salary, 2)); ?></td>
                            <td style="color: #34d399;">$<?php echo e(number_format($pos->max_salary, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/departments/index.blade.php ENDPATH**/ ?>