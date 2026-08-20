<?php $__env->startSection('content'); ?>
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">
    <div>
        <a href="<?php echo e(route('employees.index')); ?>" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            <span>Back to Directory</span>
        </a>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Employee Registration Wizard</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Register a new workforce member and initialize their profile, department, and salary grade.</p>
    </div>

    <form action="<?php echo e(route('employees.store')); ?>" method="POST" class="glass-panel" style="padding: 2rem; display: flex; flex-direction: column; gap: 1.75rem;">
        <?php echo csrf_field(); ?>

        <!-- Section 1: Basic Info -->
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #818cf8; margin-bottom: 1rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">1. Basic Personal Information</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label">Employee Number (Auto Generated)</label>
                    <input type="text" name="employee_number" value="<?php echo e(old('employee_number', $autoNumber)); ?>" readonly class="form-control" style="background: rgba(0,0,0,0.4); color: #818cf8; font-weight: 700;">
                </div>

                <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-control" required>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                        <option value="Non-Binary">Non-Binary</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Date of Birth *</label>
                    <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth', '1995-01-01')); ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">National ID Number *</label>
                    <input type="text" name="national_id" value="<?php echo e(old('national_id')); ?>" required placeholder="NID-XXXXXX-XXX" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label">Work Email Address *</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="john.doe@company.com" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number *</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required placeholder="+1 (555) 000-0000" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Physical Address</label>
                <textarea name="address" rows="2" class="form-control" placeholder="Street address, city, state, postal code..."><?php echo e(old('address')); ?></textarea>
            </div>
        </div>

        <!-- Section 2: Employment & Assignment -->
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #34d399; margin-bottom: 1rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">2. Employment & Department Assignment</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <select name="department_id" class="form-control" required>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->name); ?> (<?php echo e($dept->code); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Position *</label>
                    <select name="position_id" class="form-control" required>
                        <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pos->id); ?>"><?php echo e($pos->title); ?> [<?php echo e($pos->salary_grade); ?>]</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Date Hired *</label>
                    <input type="date" name="date_hired" value="<?php echo e(old('date_hired', date('Y-m-d'))); ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Employment Type *</label>
                    <select name="employment_type" class="form-control" required>
                        <option value="Full-Time">Full-Time</option>
                        <option value="Part-Time">Part-Time</option>
                        <option value="Contract">Contract</option>
                        <option value="Probation">Probation</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Initial Employment Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="Active">Active</option>
                        <option value="Probation">Probation</option>
                        <option value="Contract">Contract</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Salary Grade *</label>
                    <select name="salary_grade" class="form-control" required>
                        <option value="Grade 1">Grade 1 (Entry Level)</option>
                        <option value="Grade 2" selected>Grade 2 (Intermediate)</option>
                        <option value="Grade 3">Grade 3 (Senior Specialist)</option>
                        <option value="Grade 4">Grade 4 (Managerial)</option>
                        <option value="Executive Grade 5">Executive Grade 5 (Director/VP)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 3: Compensation & Allowances -->
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #fbbf24; margin-bottom: 1rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">3. Compensation Structure ($)</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label">Basic Monthly Salary ($) *</label>
                    <input type="number" step="0.01" name="basic_salary" value="<?php echo e(old('basic_salary', '55000.00')); ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Housing Allowance ($)</label>
                    <input type="number" step="0.01" name="housing_allowance" value="<?php echo e(old('housing_allowance', '8000.00')); ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Transport Allowance ($)</label>
                    <input type="number" step="0.01" name="transport_allowance" value="<?php echo e(old('transport_allowance', '5000.00')); ?>" required class="form-control">
                </div>
            </div>
        </div>

        <!-- Section 4: Emergency Contacts -->
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-muted); margin-bottom: 1rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">4. Emergency Contact Details</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label">Contact Name</label>
                    <input type="text" name="emergency_contact_name" value="<?php echo e(old('emergency_contact_name')); ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="emergency_contact_phone" value="<?php echo e(old('emergency_contact_phone')); ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Relationship</label>
                    <input type="text" name="emergency_contact_relationship" value="<?php echo e(old('emergency_contact_relationship')); ?>" placeholder="Spouse, Parent, Sibling..." class="form-control">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
            <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                <i data-lucide="check" style="width: 18px; height: 18px;"></i>
                <span>Complete Registration</span>
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/employees/create.blade.php ENDPATH**/ ?>