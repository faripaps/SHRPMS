<?php $__env->startSection('content'); ?>
<div style="display: flex; flex-direction: column; gap: 1.5rem;" x-data="{ showClockModal: false }">
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Attendance & Time Management</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Track daily employee work attendance, time-in/time-out, overtime hours, and absence deductions.</p>
        </div>

        <button @click="showClockModal = true" class="btn btn-primary">
            <i data-lucide="clock" style="width: 18px; height: 18px;"></i>
            <span>Log Time Punch / Overtime</span>
        </button>
    </div>

    <!-- Date Picker Filter & Counter Cards -->
    <div class="glass-panel" style="padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <form action="<?php echo e(route('attendance.index')); ?>" method="GET" style="display: flex; align-items: center; gap: 0.75rem;">
            <label class="form-label" style="margin: 0; white-space: nowrap;">Select Date Register:</label>
            <input type="date" name="date" value="<?php echo e($selectedDate); ?>" onchange="this.form.submit()" class="form-control" style="width: auto;">
        </form>

        <div style="display: flex; gap: 1.5rem; font-size: 0.875rem;">
            <div><span style="color: var(--text-muted);">Present:</span> <strong style="color: #34d399;"><?php echo e($presentCount); ?></strong></div>
            <div><span style="color: var(--text-muted);">Late Arrival:</span> <strong style="color: #fbbf24;"><?php echo e($lateCount); ?></strong></div>
            <div><span style="color: var(--text-muted);">Overtime Logs:</span> <strong style="color: #818cf8;"><?php echo e($overtimeCount); ?></strong></div>
            <div><span style="color: var(--text-muted);">Absent:</span> <strong style="color: #f87171;"><?php echo e($absentCount); ?></strong></div>
        </div>
    </div>

    <!-- Clock Punch Modal -->
    <div x-show="showClockModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 1rem;" x-cloak>
        <div class="glass-panel" style="width: 100%; max-width: 500px; padding: 2rem;" @click.away="showClockModal = false">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Attendance & Overtime Punch Clock</h2>
                <button @click="showClockModal = false" style="background: none; border: none; color: var(--text-muted); cursor: pointer;"><i data-lucide="x"></i></button>
            </div>

            <form action="<?php echo e(route('attendance.store')); ?>" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label class="form-label">Employee *</label>
                    <select name="employee_id" class="form-control" required>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->full_name); ?> (<?php echo e($emp->employee_number); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Attendance Date *</label>
                    <input type="date" name="date" value="<?php echo e($selectedDate); ?>" required class="form-control">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Clock In Time</label>
                        <input type="time" name="clock_in" value="08:30" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Clock Out Time</label>
                        <input type="time" name="clock_out" value="17:30" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Status Category *</label>
                        <select name="status" class="form-control" required>
                            <option value="Present">Present</option>
                            <option value="Late">Late Arrival</option>
                            <option value="Overtime">Overtime Worked</option>
                            <option value="Absent">Absent</option>
                            <option value="On Leave">On Leave</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Overtime Hours</label>
                        <input type="number" step="0.5" name="overtime_hours" value="0.0" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Notes & Remarks</label>
                    <input type="text" name="notes" placeholder="Reason for late arrival / overtime project..." class="form-control">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
                    <button type="button" @click="showClockModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Attendance Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="glass-panel" style="overflow: hidden;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Clock In / Out</th>
                    <th>Work Hours</th>
                    <th>Overtime Hours</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #fff;"><?php echo e($rec->employee->full_name); ?></div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);"><?php echo e($rec->employee->employee_number); ?></div>
                        </td>
                        <td>
                            <div style="color: #fff; font-size: 0.85rem;"><?php echo e($rec->employee->department->name ?? 'N/A'); ?></div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #818cf8; font-size: 0.85rem;">
                                <?php echo e($rec->clock_in ? date('h:i A', strtotime($rec->clock_in)) : '--:--'); ?> - 
                                <?php echo e($rec->clock_out ? date('h:i A', strtotime($rec->clock_out)) : '--:--'); ?>

                            </div>
                        </td>
                        <td>
                            <strong style="color: #fff;"><?php echo e(number_format($rec->work_hours, 1)); ?> hrs</strong>
                        </td>
                        <td>
                            <?php if($rec->overtime_hours > 0): ?>
                                <strong style="color: #34d399;">+<?php echo e(number_format($rec->overtime_hours, 1)); ?> hrs</strong>
                            <?php else: ?>
                                <span style="color: var(--text-dim);">0.0 hrs</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e(strtolower($rec->status)); ?>"><?php echo e($rec->status); ?></span>
                        </td>
                        <td style="color: var(--text-muted); font-size: 0.8rem;"><?php echo e($rec->notes ?? '--'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem;">No attendance records logged for <?php echo e(date('M d, Y', strtotime($selectedDate))); ?>.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border);">
            <?php echo e($records->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/attendance/index.blade.php ENDPATH**/ ?>