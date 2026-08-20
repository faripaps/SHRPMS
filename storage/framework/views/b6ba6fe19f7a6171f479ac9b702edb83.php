<?php $__env->startSection('content'); ?>
<div style="display: flex; flex-direction: column; gap: 1.5rem;" x-data="{ reportTab: 'hr' }">
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Workforce & Financial Analytics Reports</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Generate executive HR metrics, leave utilization summaries, tax deduction breakdowns, and payroll cost analysis.</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div style="display: flex; gap: 0.5rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">
        <button @click="reportTab = 'hr'" :class="{ 'btn-primary': reportTab === 'hr', 'btn-secondary': reportTab !== 'hr' }" class="btn">
            <i data-lucide="users" style="width: 16px; height: 16px;"></i>
            <span>HR & Workforce Reports</span>
        </button>
        <button @click="reportTab = 'payroll'" :class="{ 'btn-primary': reportTab === 'payroll', 'btn-secondary': reportTab !== 'payroll' }" class="btn">
            <i data-lucide="pie-chart" style="width: 16px; height: 16px;"></i>
            <span>Financial & Tax Reports</span>
        </button>
    </div>

    <!-- Tab 1: HR Reports -->
    <div x-show="reportTab === 'hr'" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
            <div class="glass-panel" style="padding: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">Department Staff Distribution</h3>
                <table class="custom-table">
                    <thead><tr><th>Department</th><th>Head of Dept</th><th>Staff Count</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $employeesByDept; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="font-weight: 700; color: #fff;"><?php echo e($dept->name); ?></td>
                                <td><?php echo e($dept->headOfDepartment->full_name ?? 'N/A'); ?></td>
                                <td><strong style="color: #34d399;"><?php echo e($dept->employees_count); ?> Members</strong></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="glass-panel" style="padding: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">Leave Utilization Summary</h3>
                <table class="custom-table">
                    <thead><tr><th>Leave Type</th><th>Total Applications</th><th>Days Taken</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $leavesSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="font-weight: 700; color: #818cf8;"><?php echo e($ls->leaveType->name ?? 'General'); ?></td>
                                <td><?php echo e($ls->total_requests); ?> Requests</td>
                                <td><strong style="color: #fbbf24;"><?php echo e($ls->total_days_taken); ?> Days</strong></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 2: Financial & Tax Reports -->
    <div x-show="reportTab === 'payroll'" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="glass-panel" style="padding: 1.75rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: 1.25rem;">Latest Payroll Deduction & Tax Summary Statement</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                <div style="background: rgba(15,23,42,0.6); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Total Basic Salary</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #fff;">$<?php echo e(number_format($totalBasic, 2)); ?></div>
                </div>
                <div style="background: rgba(15,23,42,0.6); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Total Allowances & Overtime</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #34d399;">+$<?php echo e(number_format($totalAllowances, 2)); ?></div>
                </div>
                <div style="background: rgba(15,23,42,0.6); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Income Tax (PAYE)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #f87171;">-$<?php echo e(number_format($totalTax, 2)); ?></div>
                </div>
                <div style="background: rgba(15,23,42,0.6); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Pension Fund (8%)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #f87171;">-$<?php echo e(number_format($totalPension, 2)); ?></div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem;">
                <div style="background: rgba(15,23,42,0.6); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Social Security (5%)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #f87171;">-$<?php echo e(number_format($totalSocialSecurity, 2)); ?></div>
                </div>
                <div style="background: rgba(15,23,42,0.6); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Medical Aid (3%)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #f87171;">-$<?php echo e(number_format($totalMedicalAid, 2)); ?></div>
                </div>
                <div style="background: linear-gradient(135deg, rgba(99,102,241,0.3), rgba(16,185,129,0.3)); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid rgba(99,102,241,0.5); grid-column: span 2;">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #fff;">TOTAL NET PAY DISBURSEMENT</div>
                    <div style="font-size: 1.85rem; font-weight: 900; color: #34d399;">$<?php echo e(number_format($totalNetPay, 2)); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/reports/index.blade.php ENDPATH**/ ?>