@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">
    <!-- Dashboard Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff; letter-spacing: -0.02em;">Executive Workforce Dashboard</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Real-time workforce metrics, departmental analytics, and payroll management controls.</p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                <span>Add Employee</span>
            </a>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                <i data-lucide="calculator" style="width: 18px; height: 18px;"></i>
                <span>Run Payroll</span>
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
        <!-- KPI 1 -->
        <div class="glass-panel stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Total Workforce</span>
                <div class="stat-icon" style="background: rgba(99, 102, 241, 0.2); color: #818cf8;">
                    <i data-lucide="users"></i>
                </div>
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">{{ $totalEmployees }}</div>
            <div style="font-size: 0.8rem; color: #34d399; display: flex; align-items: center; gap: 0.35rem;">
                <i data-lucide="trending-up" style="width: 14px; height: 14px;"></i>
                <span>{{ $activeEmployees }} Active / {{ $probationEmployees }} Probation</span>
            </div>
        </div>

        <!-- KPI 2 -->
        <div class="glass-panel stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Employees On Leave</span>
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">
                    <i data-lucide="calendar"></i>
                </div>
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">{{ $employeesOnLeave }}</div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">Active leave approvals today</div>
        </div>

        <!-- KPI 3 -->
        <div class="glass-panel stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Monthly Payroll Expense</span>
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.2); color: #34d399;">
                    <i data-lucide="dollar-sign"></i>
                </div>
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">${{ number_format($monthlyPayrollCost, 2) }}</div>
            <div style="font-size: 0.8rem; color: #34d399;">Gross monthly commitment</div>
        </div>

        <!-- KPI 4 -->
        <div class="glass-panel stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Employee Turnover Rate</span>
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.2); color: #f87171;">
                    <i data-lucide="user-minus"></i>
                </div>
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">{{ $turnoverRate }}%</div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">Annualized attrition index</div>
        </div>
    </div>

    <!-- Analytics Charts Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
        <!-- Donut Chart: Department Distribution -->
        <div class="glass-panel" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #fff;">Department Staff Distribution</h2>
                <i data-lucide="pie-chart" style="color: var(--text-muted); width: 18px; height: 18px;"></i>
            </div>
            <div style="position: relative; height: 260px; display: flex; justify-content: center;">
                <canvas id="deptChart"></canvas>
            </div>
        </div>

        <!-- Area Chart: Monthly Payroll Trends -->
        <div class="glass-panel" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #fff;">Monthly Payroll Growth Trend</h2>
                <i data-lucide="trending-up" style="color: var(--text-muted); width: 18px; height: 18px;"></i>
            </div>
            <div style="position: relative; height: 260px;">
                <canvas id="payrollChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Lists Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
        <!-- Recent Leave Requests -->
        <div class="glass-panel" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h2 style="font-size: 1.05rem; font-weight: 700; color: #fff;">Recent Leave Applications</h2>
                <a href="{{ route('leave.index') }}" style="font-size: 0.8rem; color: var(--primary); text-decoration: none; font-weight: 600;">View All</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @forelse($recentLeaves as $leave)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem; background: rgba(15, 23, 42, 0.5); border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                        <div>
                            <div style="font-weight: 600; color: #fff; font-size: 0.9rem;">{{ $leave->employee->full_name }}</div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);">{{ $leave->leaveType->name }} &bull; {{ $leave->total_days }} Days</div>
                        </div>
                        <span class="badge badge-{{ strtolower($leave->status) }}">{{ $leave->status }}</span>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.875rem;">No recent leave applications.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Employee Onboardings -->
        <div class="glass-panel" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h2 style="font-size: 1.05rem; font-weight: 700; color: #fff;">Workforce Additions</h2>
                <a href="{{ route('employees.index') }}" style="font-size: 0.8rem; color: var(--primary); text-decoration: none; font-weight: 600;">Directory</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @forelse($recentEmployees as $emp)
                    <div style="display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem; background: rgba(15, 23, 42, 0.5); border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                        <img src="{{ $emp->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100' }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="Avatar">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; color: #fff; font-size: 0.875rem;">{{ $emp->full_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $emp->department->name ?? 'General' }} &bull; {{ $emp->employee_number }}</div>
                        </div>
                        <span class="badge badge-active">{{ $emp->status }}</span>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.875rem;">No recent employees.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Department Donut Chart
        const deptCtx = document.getElementById('deptChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($deptLabels) !!},
                datasets: [{
                    data: {!! json_encode($deptCounts) !!},
                    backgroundColor: ['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                    }
                },
                cutout: '70%'
            }
        });

        // Payroll Area Chart
        const payrollCtx = document.getElementById('payrollChart').getContext('2d');
        new Chart(payrollCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [{
                    label: 'Monthly Payroll Expense ($)',
                    data: [410000, 425000, 430000, 442000, 450000, 468000, 485000, {{ $monthlyPayrollCost }}],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } }
                },
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection
