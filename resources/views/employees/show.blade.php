@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;" x-data="{ activeTab: 'profile' }">
    <!-- Top Nav -->
    <div>
        <a href="{{ route('employees.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            <span>Back to Employee Directory</span>
        </a>
    </div>

    <!-- Employee Header Card -->
    <div class="glass-panel" style="padding: 1.75rem; display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <img src="{{ $employee->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150' }}" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);" alt="Avatar">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                    <h1 style="font-size: 1.5rem; font-weight: 800; color: #fff;">{{ $employee->full_name }}</h1>
                    <span class="badge badge-{{ strtolower($employee->status) }}">{{ $employee->status }}</span>
                </div>
                <div style="font-size: 0.875rem; color: var(--text-muted);">
                    {{ $employee->position->title ?? 'Position N/A' }} &bull; <strong style="color: #818cf8;">{{ $employee->department->name ?? 'Dept N/A' }}</strong>
                </div>
                <div style="font-size: 0.775rem; color: var(--text-dim); margin-top: 0.25rem;">
                    ID: {{ $employee->employee_number }} &bull; Hired: {{ $employee->date_hired->format('F d, Y') }} &bull; Grade: {{ $employee->salary_grade }}
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('payslips.index', ['employee_id' => $employee->id]) }}" class="btn btn-secondary">
                <i data-lucide="file-text" style="width: 16px; height: 16px;"></i>
                <span>View Payslips</span>
            </a>
            <button @click="activeTab = 'edit'" class="btn btn-primary">
                <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i>
                <span>Edit Profile</span>
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div style="display: flex; gap: 0.5rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">
        <button @click="activeTab = 'profile'" :class="{ 'btn-primary': activeTab === 'profile', 'btn-secondary': activeTab !== 'profile' }" class="btn" style="padding: 0.5rem 1.25rem;">
            <i data-lucide="user" style="width: 16px; height: 16px;"></i>
            <span>Overview & Info</span>
        </button>
        <button @click="activeTab = 'lifecycle'" :class="{ 'btn-primary': activeTab === 'lifecycle', 'btn-secondary': activeTab !== 'lifecycle' }" class="btn" style="padding: 0.5rem 1.25rem;">
            <i data-lucide="git-commit" style="width: 16px; height: 16px;"></i>
            <span>Lifecycle Timeline ({{ $employee->lifecycleEvents->count() }})</span>
        </button>
        <button @click="activeTab = 'leave'" :class="{ 'btn-primary': activeTab === 'leave', 'btn-secondary': activeTab !== 'leave' }" class="btn" style="padding: 0.5rem 1.25rem;">
            <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
            <span>Leave Entitlements</span>
        </button>
    </div>

    <!-- Tab 1: Profile Overview -->
    <div x-show="activeTab === 'profile'" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">Contact & Personal Details</h3>
            <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Email:</span><strong style="color: #fff;">{{ $employee->email }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Phone:</span><strong style="color: #fff;">{{ $employee->phone }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Gender:</span><strong style="color: #fff;">{{ $employee->gender }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Date of Birth:</span><strong style="color: #fff;">{{ $employee->date_of_birth->format('M d, Y') }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">National ID:</span><strong style="color: #818cf8;">{{ $employee->national_id }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Address:</span><strong style="color: #fff;">{{ $employee->address ?? 'N/A' }}</strong></div>
            </div>
        </div>

        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #34d399; margin-bottom: 1.25rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">Compensation & Pay Structure</h3>
            <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Basic Monthly Salary:</span><strong style="color: #34d399;">${{ number_format($employee->basic_salary, 2) }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Housing Allowance:</span><strong style="color: #fff;">${{ number_format($employee->housing_allowance, 2) }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Transport Allowance:</span><strong style="color: #fff;">${{ number_format($employee->transport_allowance, 2) }}</strong></div>
                <div style="display: flex; justify-content: space-between; border-top: 1px dashed var(--bg-card-border); padding-top: 0.5rem;"><span style="color: #fff; font-weight: 700;">Total Monthly Package:</span><strong style="color: #818cf8; font-size: 1.05rem;">${{ number_format($employee->basic_salary + $employee->housing_allowance + $employee->transport_allowance, 2) }}</strong></div>
            </div>
        </div>

        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #fbbf24; margin-bottom: 1.25rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem;">Emergency Contact Info</h3>
            <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Contact Name:</span><strong style="color: #fff;">{{ $employee->emergency_contact_name ?? 'N/A' }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Phone Number:</span><strong style="color: #fff;">{{ $employee->emergency_contact_phone ?? 'N/A' }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted);">Relationship:</span><strong style="color: #fbbf24;">{{ $employee->emergency_contact_relationship ?? 'N/A' }}</strong></div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Lifecycle Timeline -->
    <div x-show="activeTab === 'lifecycle'" class="glass-panel" style="padding: 1.5rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">Employment Audit & Career Progressions</h3>
        
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            @forelse($employee->lifecycleEvents as $event)
                <div style="display: flex; gap: 1rem; border-left: 2px solid var(--primary); padding-left: 1.25rem; position: relative;">
                    <div style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></div>
                    <div style="flex: 1; background: rgba(15,23,42,0.5); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                            <strong style="color: #818cf8; font-size: 0.95rem;">{{ $event->event_type }}</strong>
                            <span style="font-size: 0.775rem; color: var(--text-muted);">Effective: {{ $event->effective_date->format('M d, Y') }}</span>
                        </div>
                        <p style="font-size: 0.875rem; color: var(--text-main); margin-bottom: 0.5rem;">{{ $event->description }}</p>
                        <div style="font-size: 0.75rem; color: var(--text-dim);">Processed by: {{ $event->performed_by }}</div>
                    </div>
                </div>
            @empty
                <p style="color: var(--text-muted); font-size: 0.9rem;">No recorded lifecycle audit events.</p>
            @endforelse
        </div>
    </div>

    <!-- Tab 3: Leave Balances -->
    <div x-show="activeTab === 'leave'" class="glass-panel" style="padding: 1.5rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">Leave Entitlements & Balances ({{ date('Y') }})</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
            @foreach($employee->leaveBalances as $balance)
                <div style="background: rgba(15,23,42,0.6); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--bg-card-border);">
                    <div style="font-weight: 700; color: #fff; font-size: 0.95rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $balance->leaveType->color_hex }}; display: inline-block;"></span>
                        {{ $balance->leaveType->name }}
                    </div>
                    <div style="font-size: 1.75rem; font-weight: 800; color: #34d399; margin-bottom: 0.5rem;">
                        {{ $balance->remaining_days }} <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">/ {{ $balance->total_entitled }} days left</span>
                    </div>
                    <div style="font-size: 0.775rem; color: var(--text-muted); display: flex; justify-content: space-between;">
                        <span>Used: {{ $balance->used_days }} days</span>
                        <span>Pending: {{ $balance->pending_days }} days</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
