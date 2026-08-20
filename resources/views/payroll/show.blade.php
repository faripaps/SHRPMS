@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    <div>
        <a href="{{ route('payroll.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            <span>Back to Payroll Runs</span>
        </a>
        <div style="display: flex; align-items: center; justify-content: space-between; wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 800; color: #fff;">Payroll Register: {{ $payroll->batch_reference }}</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Period: {{ date('F Y', mktime(0, 0, 0, $payroll->payroll_month, 1, $payroll->payroll_year)) }} &bull; Staff: {{ $payroll->total_employees }} Employees &bull; Status: <span class="badge badge-approved">{{ $payroll->status }}</span>
                </p>
            </div>

            <div style="font-size: 1.25rem; font-weight: 800; color: #34d399; background: rgba(16, 185, 129, 0.15); padding: 0.75rem 1.25rem; border-radius: var(--radius-sm); border: 1px solid rgba(16, 185, 129, 0.3);">
                Total Net Disbursement: ${{ number_format($payroll->total_net_pay, 2) }}
            </div>
        </div>
    </div>

    <!-- Register Table -->
    <div class="glass-panel" style="overflow-x: auto;">
        <table class="custom-table" style="min-width: 1100px;">
            <thead>
                <tr>
                    <th>Payslip No</th>
                    <th>Employee</th>
                    <th>Basic Salary</th>
                    <th>Allowances</th>
                    <th>Overtime</th>
                    <th>Gross Pay</th>
                    <th>Tax (PAYE)</th>
                    <th>Pension (8%)</th>
                    <th>Net Pay</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payroll->payslips as $ps)
                    <tr>
                        <td style="font-weight: 600; color: #818cf8;">{{ $ps->payslip_number }}</td>
                        <td>
                            <div style="font-weight: 700; color: #fff;">{{ $ps->employee->full_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $ps->employee->department->name ?? '' }}</div>
                        </td>
                        <td>${{ number_format($ps->basic_salary, 2) }}</td>
                        <td>${{ number_format($ps->housing_allowance + $ps->transport_allowance, 2) }}</td>
                        <td style="color: #34d399;">+${{ number_format($ps->overtime_pay, 2) }}</td>
                        <td style="font-weight: 700; color: #fff;">${{ number_format($ps->gross_pay, 2) }}</td>
                        <td style="color: #f87171;">-${{ number_format($ps->income_tax, 2) }}</td>
                        <td style="color: #f87171;">-${{ number_format($ps->pension, 2) }}</td>
                        <td>
                            <strong style="color: #34d399; font-size: 1rem;">${{ number_format($ps->net_pay, 2) }}</strong>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('payslips.show', $ps->id) }}" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;">
                                <i data-lucide="file-text" style="width: 14px; height: 14px;"></i>
                                <span>Payslip</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
