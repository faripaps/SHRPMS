<?php $__env->startSection('content'); ?>
<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Employee Directory</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Maintain complete employee records, positions, salary grades, and contact info.</p>
        </div>

        <a href="<?php echo e(route('employees.create')); ?>" class="btn btn-primary">
            <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
            <span>Register Employee</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="glass-panel" style="padding: 1.25rem;">
        <form action="<?php echo e(route('employees.index')); ?>" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <div>
                <label class="form-label">Search Employees</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name, ID, or Email..." class="form-control">
            </div>

            <div>
                <label class="form-label">Department</label>
                <select name="department_id" class="form-control">
                    <option value="">All Departments</option>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department_id') == $dept->id ? 'selected' : ''); ?>><?php echo e($dept->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="Active" <?php echo e(request('status') == 'Active' ? 'selected' : ''); ?>>Active</option>
                    <option value="Probation" <?php echo e(request('status') == 'Probation' ? 'selected' : ''); ?>>Probation</option>
                    <option value="Contract" <?php echo e(request('status') == 'Contract' ? 'selected' : ''); ?>>Contract</option>
                    <option value="Suspended" <?php echo e(request('status') == 'Suspended' ? 'selected' : ''); ?>>Suspended</option>
                    <option value="Resigned" <?php echo e(request('status') == 'Resigned' ? 'selected' : ''); ?>>Resigned</option>
                    <option value="Terminated" <?php echo e(request('status') == 'Terminated' ? 'selected' : ''); ?>>Terminated</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i data-lucide="search" style="width: 16px; height: 16px;"></i>
                    <span>Filter</span>
                </button>
                <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Employee Directory Table -->
    <div class="glass-panel" style="overflow: hidden;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>ID & Contact</th>
                    <th>Department & Position</th>
                    <th>Salary Grade</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <img src="<?php echo e($emp->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100'); ?>" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover;" alt="Avatar">
                                <div>
                                    <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?php echo e($emp->full_name); ?></div>
                                    <div style="font-size: 0.775rem; color: var(--text-muted);">Hired: <?php echo e($emp->date_hired->format('M d, Y')); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #818cf8; font-size: 0.85rem;"><?php echo e($emp->employee_number); ?></div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);"><?php echo e($emp->email); ?></div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #fff;"><?php echo e($emp->department->name ?? 'N/A'); ?></div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);"><?php echo e($emp->position->title ?? 'N/A'); ?></div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #34d399;">$<?php echo e(number_format($emp->basic_salary, 2)); ?></div>
                            <div style="font-size: 0.725rem; color: var(--text-dim);"><?php echo e($emp->salary_grade); ?></div>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e(strtolower($emp->status)); ?>"><?php echo e($emp->status); ?></span>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?php echo e(route('employees.show', $emp->id)); ?>" class="btn btn-secondary" style="padding: 0.4rem 0.85rem; font-size: 0.8rem;">
                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                <span>Profile</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">No employee records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border);">
            <?php echo e($employees->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/employees/index.blade.php ENDPATH**/ ?>