<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart HR & Payroll Management System</title>
    
    <!-- Design CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    
    <!-- Alpine.js & Chart.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <?php
        $activeRole = session('demo_role', auth()->user()?->role ?? 'hr_admin');
        $roleNames = [
            'hr_admin' => 'HR Administrator',
            'manager' => 'Department Manager',
            'employee' => 'Employee Self-Service'
        ];
    ?>

    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="glass-sidebar" style="width: 280px; flex-shrink: 0; display: flex; flex-direction: column;">
            <div style="padding: 1.5rem 1.25rem; border-bottom: 1px solid var(--bg-card-border); display: flex; align-items: center; gap: 0.85rem;">
                <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #6366f1, #06b6d4); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem; color: #fff;">
                    HR
                </div>
                <div>
                    <h1 style="font-size: 1.05rem; font-weight: 700; color: #fff; line-height: 1.2;">Workforce HRMS</h1>
                    <span style="font-size: 0.725rem; color: var(--text-muted);">Enterprise Payroll & HR</span>
                </div>
            </div>

            <nav style="padding: 1.25rem 0.85rem; flex: 1; overflow-y: auto;">
                <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.08em; padding: 0 0.75rem 0.5rem 0.75rem;">
                    Core Navigation
                </div>

                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <i data-lucide="layout-dashboard" style="width: 20px; height: 20px;"></i>
                    <span>Dashboard</span>
                </a>

                <?php if(in_array($activeRole, ['hr_admin', 'manager'])): ?>
                <a href="<?php echo e(route('employees.index')); ?>" class="nav-link <?php echo e(request()->routeIs('employees.*') ? 'active' : ''); ?>">
                    <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                    <span>Employee Records</span>
                </a>

                <a href="<?php echo e(route('lifecycle.index')); ?>" class="nav-link <?php echo e(request()->routeIs('lifecycle.*') ? 'active' : ''); ?>">
                    <i data-lucide="git-commit" style="width: 20px; height: 20px;"></i>
                    <span>Employment Lifecycle</span>
                </a>
                <?php endif; ?>

                <a href="<?php echo e(route('leave.index')); ?>" class="nav-link <?php echo e(request()->routeIs('leave.*') ? 'active' : ''); ?>">
                    <i data-lucide="calendar-off" style="width: 20px; height: 20px;"></i>
                    <span>Leave Management</span>
                </a>

                <a href="<?php echo e(route('attendance.index')); ?>" class="nav-link <?php echo e(request()->routeIs('attendance.*') ? 'active' : ''); ?>">
                    <i data-lucide="clock" style="width: 20px; height: 20px;"></i>
                    <span>Attendance & Time</span>
                </a>

                <?php if(in_array($activeRole, ['hr_admin', 'manager'])): ?>
                <a href="<?php echo e(route('payroll.index')); ?>" class="nav-link <?php echo e(request()->routeIs('payroll.*') ? 'active' : ''); ?>">
                    <i data-lucide="calculator" style="width: 20px; height: 20px;"></i>
                    <span>Payroll Processing</span>
                </a>
                <?php endif; ?>

                <a href="<?php echo e(route('payslips.index')); ?>" class="nav-link <?php echo e(request()->routeIs('payslips.*') ? 'active' : ''); ?>">
                    <i data-lucide="file-text" style="width: 20px; height: 20px;"></i>
                    <span>Payslip Records</span>
                </a>

                <?php if(in_array($activeRole, ['hr_admin', 'manager'])): ?>
                <a href="<?php echo e(route('departments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('departments.*') ? 'active' : ''); ?>">
                    <i data-lucide="building-2" style="width: 20px; height: 20px;"></i>
                    <span>Org Structure</span>
                </a>

                <a href="<?php echo e(route('reports.index')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                    <i data-lucide="bar-chart-3" style="width: 20px; height: 20px;"></i>
                    <span>Reports & Analytics</span>
                </a>
                <?php endif; ?>
            </nav>

            <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border); background: rgba(0,0,0,0.2);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&auto=format&fit=crop&q=80" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;" alt="User">
                    <div style="min-width: 0;">
                        <div style="font-size: 0.875rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Sarah Jenkins</div>
                        <div style="font-size: 0.725rem; color: var(--text-muted);"><?php echo e($roleNames[$activeRole]); ?></div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Role Switcher Bar -->
            <div class="role-banner">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="font-weight: 600; color: #cbd5e1;">Active Persona:</span>
                    <span class="role-badge <?php echo e($activeRole); ?>">
                        <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i>
                        <?php echo e($roleNames[$activeRole]); ?>

                    </span>
                </div>

                <!-- Switch Role Form -->
                <form action="<?php echo e(route('switch-role')); ?>" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                    <?php echo csrf_field(); ?>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Switch Persona:</span>
                    <select name="role" onchange="this.form.submit()" style="background: rgba(15, 23, 42, 0.9); color: #fff; border: 1px solid var(--bg-card-border); padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.8rem; outline: none; cursor: pointer;">
                        <option value="hr_admin" <?php echo e($activeRole === 'hr_admin' ? 'selected' : ''); ?>>HR Administrator (Full Access)</option>
                        <option value="manager" <?php echo e($activeRole === 'manager' ? 'selected' : ''); ?>>Department Manager View</option>
                        <option value="employee" <?php echo e($activeRole === 'employee' ? 'selected' : ''); ?>>Employee Self-Service View</option>
                    </select>
                </form>
            </div>

            <!-- Page Content -->
            <main class="content-body">
                <?php if(session('success')): ?>
                    <div class="alert-toast alert-success">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i data-lucide="check-circle-2" style="width: 20px; height: 20px;"></i>
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert-toast alert-danger">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
                            <span><?php echo e(session('error')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
<?php /**PATH C:\Users\bambo1\Documents\Smart Human Resource and Payroll Management System for Organizational Workforce Administration\resources\views/layouts/app.blade.php ENDPATH**/ ?>