@extends('layouts.app')

@section('content')
<div style="max-width: 850px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <a href="{{ route('payslips.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 0.35rem;">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            <span>Back to Payslips</span>
        </a>

        <a href="{{ route('payslips.pdf', $payslip->id) }}" class="btn btn-primary">
            <i data-lucide="download" style="width: 16px; height: 16px;"></i>
            <span>Export Official PDF Payslip</span>
        </a>
    </div>

    <!-- Official Corporate Payslip Card -->
    <div class="glass-panel" style="padding: 2.5rem; background: rgba(15, 23, 42, 0.95); border: 1px solid var(--bg-card-border);">
        <!-- Company Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid var(--primary); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #fff;">WORKFORCE ADMINISTRATION CORP</h2>
                <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.4;">Headquarters Campus, Executive Tower Suite 500<br>Official Monthly Salary Statement</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 1.25rem; font-weight: 800; color: #818cf8;">PAYSLIP STATEMENT</div>
                <div style="font-size: 0.85rem; color: #fff; font-weight: 600;">Ref: {{ $payslip->payslip_number }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">Period: {{ date('F Y', mktime(0, 0, 0, $payslip->payroll_month, 1, $payslip->payroll_year)) }}</div>
            </div>
        </div>

        <!-- Employee Summary Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; background: rgba(30, 41, 59, 0.5); padding: 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.75rem; border: 1px solid var(--bg-card-border);">
            <div>
                <div style="font-size: 0.775rem; color: var(--text-muted); text-transform: uppercase;">Employee Name</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #fff;">{{ $payslip->employee->full_name }}</div>
                <div style="font-size: 0.85rem; color: #818cf8; font-weight: 600;">ID: {{ $payslip->employee->employee_number }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.775rem; color: var(--text-muted); text-transform: uppercase;">Department & Position</div>
                <div style="font-size: 0.95rem; font-weight: 600; color: #fff;">{{ $payslip->employee->department->name ?? 'N/A' }}</div>
                <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $payslip->employee->position->title ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Earnings & Deductions Breakdown Columns -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <!-- Earnings -->
            <div>
                <h3 style="font-size: 1rem; font-weight: 700; color: #34d399; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem; margin-bottom: 0.75rem;">EARNINGS</h3>
                <table style="width: 100%; font-size: 0.875rem;">
                    <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Basic Salary</td><td style="text-align: right; color: #fff; font-weight: 600;">${{ number_format($payslip->basic_salary, 2) }}</td></tr>
                    <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Housing Allowance</td><td style="text-align: right; color: #fff;">${{ number_format($payslip->housing_allowance, 2) }}</td></tr>
                    <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Transport Allowance</td><td style="text-align: right; color: #fff;">${{ number_format($payslip->transport_allowance, 2) }}</td></tr>
                    <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Overtime Pay</td><td style="text-align: right; color: #34d399;">+${{ number_format($payslip->overtime_pay, 2) }}</td></tr>
                    @if($payslip->bonus > 0)<tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Bonus Pay</td><td style="text-align: right; color: #34d399;">+${{ number_format($payslip->bonus, 2) }}</td></tr>@endif
                    <tr style="border-top: 1px solid var(--bg-card-border);"><td style="padding: 0.6rem 0; font-weight: 700; color: #fff;">GROSS EARNINGS</td><td style="text-align: right; font-weight: 700; color: #34d399; font-size: 1rem;">${{ number_format($payslip->gross_pay, 2) }}</td></tr>
                </table>
            </div>

            <!-- Deductions -->
            <div>
                <h3 style="font-size: 1rem; font-weight: 700; color: #f87171; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 0.5rem; margin-bottom: 0.75rem;">DEDUCTIONS</h3>
                <table style="width: 100%; font-size: 0.875rem;">
                    <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Income Tax (PAYE)</td><td style="text-align: right; color: #f87171;">-${{ number_format($payslip->income_tax, 2) }}</td></tr>
                    <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Pension Contribution (8%)</td><td style="text-align: right; color: #f87171;">-${{ number_format($payslip->pension, 2) }}</td></tr>
                    <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Social Security (5%)</td><td style="text-align: right; color: #f87171;">-${{ number_format($payslip->social_security, 2) }}</td></tr>
                    <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Medical Aid Scheme (3%)</td><td style="text-align: right; color: #f87171;">-${{ number_format($payslip->medical_aid, 2) }}</td></tr>
                    @if($payslip->absence_deduction > 0)<tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Absence Deductions</td><td style="text-align: right; color: #f87171;">-${{ number_format($payslip->absence_deduction, 2) }}</td></tr>@endif
                    <tr style="border-top: 1px solid var(--bg-card-border);"><td style="padding: 0.6rem 0; font-weight: 700; color: #fff;">TOTAL DEDUCTIONS</td><td style="text-align: right; font-weight: 700; color: #f87171; font-size: 1rem;">-${{ number_format($payslip->total_deductions, 2) }}</td></tr>
                </table>
            </div>
        </div>

        <!-- Net Pay Summary Callout -->
        <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(16, 185, 129, 0.2)); border: 1px solid rgba(99, 102, 241, 0.4); padding: 1.5rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">NET TAKE-HOME PAY</div>
                <div style="font-size: 0.8rem; color: #34d399;">Direct Deposit Status: Confirmed Paid</div>
            </div>
            <div style="font-size: 2.25rem; font-weight: 900; color: #34d399;">
                ${{ number_format($payslip->net_pay, 2) }}
            </div>
        </div>
    </div>
</div>
@endsection
