<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart HR & Payroll Management System</title>
    
    <!-- Design CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Alpine.js & Chart.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    @php
        $activeRole = session('demo_role', auth()->user()?->role ?? 'hr_admin');
        $roleNames = [
            'hr_admin' => 'HR Administrator',
            'manager' => 'Department Manager',
            'employee' => 'Employee Self-Service'
        ];
    @endphp

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

                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" style="width: 20px; height: 20px;"></i>
                    <span>Dashboard</span>
                </a>

                @if(in_array($activeRole, ['hr_admin', 'manager']))
                <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                    <span>Employee Records</span>
                </a>

                <a href="{{ route('lifecycle.index') }}" class="nav-link {{ request()->routeIs('lifecycle.*') ? 'active' : '' }}">
                    <i data-lucide="git-commit" style="width: 20px; height: 20px;"></i>
                    <span>Employment Lifecycle</span>
                </a>
                @endif

                <a href="{{ route('leave.index') }}" class="nav-link {{ request()->routeIs('leave.*') ? 'active' : '' }}">
                    <i data-lucide="calendar-off" style="width: 20px; height: 20px;"></i>
                    <span>Leave Management</span>
                </a>

                <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <i data-lucide="clock" style="width: 20px; height: 20px;"></i>
                    <span>Attendance & Time</span>
                </a>

                @if(in_array($activeRole, ['hr_admin', 'manager']))
                <a href="{{ route('payroll.index') }}" class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
                    <i data-lucide="calculator" style="width: 20px; height: 20px;"></i>
                    <span>Payroll Processing</span>
                </a>
                @endif

                <a href="{{ route('payslips.index') }}" class="nav-link {{ request()->routeIs('payslips.*') ? 'active' : '' }}">
                    <i data-lucide="file-text" style="width: 20px; height: 20px;"></i>
                    <span>Payslip Records</span>
                </a>

                @if(in_array($activeRole, ['hr_admin', 'manager']))
                <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                    <i data-lucide="building-2" style="width: 20px; height: 20px;"></i>
                    <span>Org Structure</span>
                </a>

                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3" style="width: 20px; height: 20px;"></i>
                    <span>Reports & Analytics</span>
                </a>
                @endif
            </nav>

            <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border); background: rgba(0,0,0,0.2);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&auto=format&fit=crop&q=80" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;" alt="User">
                    <div style="min-width: 0;">
                        <div style="font-size: 0.875rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Sarah Jenkins</div>
                        <div style="font-size: 0.725rem; color: var(--text-muted);">{{ $roleNames[$activeRole] }}</div>
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
                    <span class="role-badge {{ $activeRole }}">
                        <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i>
                        {{ $roleNames[$activeRole] }}
                    </span>
                </div>

                <!-- Switch Role Form -->
                <form action="{{ route('switch-role') }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                    @csrf
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Switch Persona:</span>
                    <select name="role" onchange="this.form.submit()" style="background: rgba(15, 23, 42, 0.9); color: #fff; border: 1px solid var(--bg-card-border); padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.8rem; outline: none; cursor: pointer;">
                        <option value="hr_admin" {{ $activeRole === 'hr_admin' ? 'selected' : '' }}>HR Administrator (Full Access)</option>
                        <option value="manager" {{ $activeRole === 'manager' ? 'selected' : '' }}>Department Manager View</option>
                        <option value="employee" {{ $activeRole === 'employee' ? 'selected' : '' }}>Employee Self-Service View</option>
                    </select>
                </form>
            </div>

            <!-- Page Content -->
            <main class="content-body">
                @if(session('success'))
                    <div class="alert-toast alert-success">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i data-lucide="check-circle-2" style="width: 20px; height: 20px;"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-toast alert-danger">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
