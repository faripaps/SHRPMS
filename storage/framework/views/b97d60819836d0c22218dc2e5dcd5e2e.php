<?php $__env->startSection('content'); ?>
<div style="display: flex; flex-direction: column; gap: 1.5rem;" x-data="{ showModal: false }">
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Employment Lifecycle Manager</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Track promotions, department transfers, salary adjustments, contract renewals, and terminations.</p>
        </div>

        <button @click="showModal = true" class="btn btn-primary">
            <i data-lucide="git-pull-request" style="width: 18px; height: 18px;"></i>
            <span>Log Lifecycle Event</span>
        </button>
    </div>

    <!-- Lifecycle Modal -->
    <div x-show="showModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 1rem;" x-cloak>
        <div class="glass-panel" style="width: 100%; max-width: 650px; padding: 2rem;" @click.away="showModal = false">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Process Employment Lifecycle Event</h2>
                <button @click="showModal = false" style="background: none; border: none; color: var(--text-muted); cursor: pointer;"><i data-lucide="x"></i></button>
            </div>

            <form action="<?php echo e(route('lifecycle.store')); ?>" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label class="form-label">Select Employee *</label>
                    <select name="employee_id" class="form-control" required>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->full_name); ?> (<?php echo e($emp->employee_number); ?>) - <?php echo e($emp->position->title ?? ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Event Category *</label>
                        <select name="event_type" class="form-control" required>
                            <option value="Promotion">Promotion</option>
                            <option value="Transfer">Department Transfer</option>
                            <option value="Salary Adjustment">Salary Adjustment</option>
                            <option value="Confirmation">Employee Confirmation</option>
                            <option value="Contract Renewal">Contract Renewal</option>
                            <option value="Resignation">Resignation Record</option>
                            <option value="Retirement">Retirement Record</option>
                            <option value="Termination">Termination Record</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Effective Date *</label>
                        <input type="date" name="effective_date" value="<?php echo e(date('Y-m-d')); ?>" required class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">New Department (If Transfer)</label>
                        <select name="new_department_id" class="form-control">
                            <option value="">No Change</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Position (If Promotion)</label>
                        <select name="new_position_id" class="form-control">
                            <option value="">No Change</option>
                            <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pos->id); ?>"><?php echo e($pos->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">New Basic Salary ($)</label>
                        <input type="number" step="0.01" name="new_salary" placeholder="Leave blank if unchanged" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Status Category</label>
                        <select name="new_status" class="form-control">
                            <option value="">No Change</option>
                            <option value="Active">Active</option>
                            <option value="Probation">Probation</option>
                            <option value="Contract">Contract</option>
                            <option value="Suspended">Suspended</option>
                            <option value="Resigned">Resigned</option>
                            <option value="Retired">Retired</option>
                            <option value="Terminated">Terminated</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Official HR Justification & Notes *</label>
                    <textarea name="description" rows="3" required placeholder="Provide executive approval rationale or details..." class="form-control"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
                    <button type="button" @click="showModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Process Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Audit Events Table -->
    <div class="glass-panel" style="overflow: hidden;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Effective Date</th>
                    <th>Employee</th>
                    <th>Event Category</th>
                    <th>Audit Description</th>
                    <th>Processed By</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight: 600; color: #818cf8;"><?php echo e($ev->effective_date->format('M d, Y')); ?></td>
                        <td>
                            <div style="font-weight: 700; color: #fff;"><?php echo e($ev->employee->full_name); ?></div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);"><?php echo e($ev->employee->department->name ?? 'N/A'); ?></div>
                        </td>
                        <td>
                            <span class="badge badge-overtime"><?php echo e($ev->event_type); ?></span>
                        </td>
                        <td style="color: var(--text-main); font-size: 0.875rem;"><?php echo e($ev->description); ?></td>
                        <td style="color: var(--text-muted); font-size: 0.8rem;"><?php echo e($ev->performed_by); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem;">No employment lifecycle audit records logged.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border);">
            <?php echo e($events->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/lifecycle/index.blade.php ENDPATH**/ ?>