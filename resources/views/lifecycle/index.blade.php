@extends('layouts.app')

@section('content')
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

            <form action="{{ route('lifecycle.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Select Employee *</label>
                    <select name="employee_id" class="form-control" required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->employee_number }}) - {{ $emp->position->title ?? '' }}</option>
                        @endforeach
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
                        <input type="date" name="effective_date" value="{{ date('Y-m-d') }}" required class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">New Department (If Transfer)</label>
                        <select name="new_department_id" class="form-control">
                            <option value="">No Change</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Position (If Promotion)</label>
                        <select name="new_position_id" class="form-control">
                            <option value="">No Change</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}">{{ $pos->title }}</option>
                            @endforeach
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
                @forelse($events as $ev)
                    <tr>
                        <td style="font-weight: 600; color: #818cf8;">{{ $ev->effective_date->format('M d, Y') }}</td>
                        <td>
                            <div style="font-weight: 700; color: #fff;">{{ $ev->employee->full_name }}</div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);">{{ $ev->employee->department->name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-overtime">{{ $ev->event_type }}</span>
                        </td>
                        <td style="color: var(--text-main); font-size: 0.875rem;">{{ $ev->description }}</td>
                        <td style="color: var(--text-muted); font-size: 0.8rem;">{{ $ev->performed_by }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem;">No employment lifecycle audit records logged.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="padding: 1.25rem; border-top: 1px solid var(--bg-card-border);">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
