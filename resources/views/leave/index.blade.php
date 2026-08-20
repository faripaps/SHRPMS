@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;" x-data="{ showLeaveModal: false }">
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Leave Management Module</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Manage employee leave entitlements, balances, application workflows, and line manager approvals.</p>
        </div>

        <button @click="showLeaveModal = true" class="btn btn-primary">
            <i data-lucide="calendar-plus" style="width: 18px; height: 18px;"></i>
            <span>Apply for Leave</span>
        </button>
    </div>

    <!-- Metric Counter Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
        <div class="glass-panel stat-card">
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Pending Approvals</div>
            <div style="font-size: 1.85rem; font-weight: 800; color: #fbbf24; margin-top: 0.25rem;">{{ $pendingCount }}</div>
        </div>
        <div class="glass-panel stat-card">
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Approved Requests</div>
            <div style="font-size: 1.85rem; font-weight: 800; color: #34d399; margin-top: 0.25rem;">{{ $approvedCount }}</div>
        </div>
        <div class="glass-panel stat-card">
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Rejected Requests</div>
            <div style="font-size: 1.85rem; font-weight: 800; color: #f87171; margin-top: 0.25rem;">{{ $rejectedCount }}</div>
        </div>
    </div>

    <!-- Apply for Leave Modal -->
    <div x-show="showLeaveModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 1rem;" x-cloak>
        <div class="glass-panel" style="width: 100%; max-width: 550px; padding: 2rem;" @click.away="showLeaveModal = false">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Submit Leave Application</h2>
                <button @click="showLeaveModal = false" style="background: none; border: none; color: var(--text-muted); cursor: pointer;"><i data-lucide="x"></i></button>
            </div>

            <form action="{{ route('leave.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Employee *</label>
                    <select name="employee_id" class="form-control" required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->employee_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Leave Category *</label>
                    <select name="leave_type_id" class="form-control" required>
                        @foreach($leaveTypes as $lt)
                            <option value="{{ $lt->id }}">{{ $lt->name }} ({{ $lt->default_days_per_year }} Days/Yr)</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Start Date *</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date *</label>
                        <input type="date" name="end_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Reason for Absence *</label>
                    <textarea name="reason" rows="3" required placeholder="Provide reason for leave application..." class="form-control"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
                    <button type="button" @click="showLeaveModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Approval Queue Table -->
    <div class="glass-panel" style="overflow: hidden;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Category</th>
                    <th>Duration & Dates</th>
                    <th>Total Days</th>
                    <th>Status</th>
                    <th style="text-align: right;">Manager Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #fff;">{{ $app->employee->full_name }}</div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);">{{ $app->employee->department->name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(99,102,241,0.2); color: #818cf8; border: 1px solid rgba(99,102,241,0.4);">
                                {{ $app->leaveType->name }}
                            </span>
                        </td>
                        <td>
                            <div style="color: #fff; font-size: 0.85rem; font-weight: 600;">{{ $app->start_date->format('M d') }} - {{ $app->end_date->format('M d, Y') }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.2; margin-top: 0.2rem;">{{ Str::limit($app->reason, 40) }}</div>
                        </td>
                        <td>
                            <strong style="color: #34d399; font-size: 1.05rem;">{{ $app->total_days }} Days</strong>
                        </td>
                        <td>
                            <span class="badge badge-{{ strtolower($app->status) }}">{{ $app->status }}</span>
                        </td>
                        <td style="text-align: right;">
                            @if($app->status === 'Pending')
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <form action="{{ route('leave.updateStatus', $app->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" class="btn btn-success" style="padding: 0.35rem 0.65rem; font-size: 0.75rem;">Approve</button>
                                    </form>

                                    <form action="{{ route('leave.updateStatus', $app->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem;">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-dim);">Processed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">No leave applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border);">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection
