<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payslip->payslip_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; padding: 20px; font-size: 12px; }
        .header { border-bottom: 2px solid #6366f1; padding-bottom: 15px; margin-bottom: 20px; }
        .company-title { font-size: 18px; font-weight: bold; color: #0f172a; }
        .payslip-title { font-size: 16px; font-weight: bold; color: #6366f1; text-align: right; }
        .grid { width: 100%; margin-bottom: 20px; }
        .box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #f1f5f9; padding: 8px; font-size: 11px; border-bottom: 1px solid #cbd5e1; }
        td { padding: 8px; border-bottom: 1px solid #f1f5f9; }
        .total-box { background: #ecfdf5; border: 1px solid #6ee7b7; padding: 15px; border-radius: 6px; margin-top: 20px; }
        .amount { font-size: 20px; font-weight: bold; color: #059669; }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <div class="company-title">WORKFORCE ADMINISTRATION CORP</div>
                    <div style="color: #64748b;">Headquarters Campus, Executive Tower Suite 500</div>
                </td>
                <td align="right">
                    <div class="payslip-title">CONFIDENTIAL PAYSLIP</div>
                    <div>Ref: {{ $payslip->payslip_number }}</div>
                    <div>Period: {{ date('F Y', mktime(0, 0, 0, $payslip->payroll_month, 1, $payslip->payroll_year)) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="box">
        <table width="100%">
            <tr>
                <td>
                    <strong>Employee Name:</strong> {{ $payslip->employee->full_name }}<br>
                    <strong>Employee ID:</strong> {{ $payslip->employee->employee_number }}
                </td>
                <td align="right">
                    <strong>Department:</strong> {{ $payslip->employee->department->name ?? 'N/A' }}<br>
                    <strong>Position:</strong> {{ $payslip->employee->position->title ?? 'N/A' }}
                </td>
            </tr>
        </table>
    </div>

    <br>

    <table width="100%">
        <tr>
            <td width="48%" valign="top">
                <h4 style="color: #059669; margin-bottom: 8px;">EARNINGS</h4>
                <table>
                    <tr><td>Basic Salary</td><td align="right">${{ number_format($payslip->basic_salary, 2) }}</td></tr>
                    <tr><td>Housing Allowance</td><td align="right">${{ number_format($payslip->housing_allowance, 2) }}</td></tr>
                    <tr><td>Transport Allowance</td><td align="right">${{ number_format($payslip->transport_allowance, 2) }}</td></tr>
                    <tr><td>Overtime Pay</td><td align="right">${{ number_format($payslip->overtime_pay, 2) }}</td></tr>
                    <tr style="font-weight: bold; background: #f8fafc;">
                        <td>GROSS PAY</td>
                        <td align="right">${{ number_format($payslip->gross_pay, 2) }}</td>
                    </tr>
                </table>
            </td>
            <td width="4%"></td>
            <td width="48%" valign="top">
                <h4 style="color: #dc2626; margin-bottom: 8px;">DEDUCTIONS</h4>
                <table>
                    <tr><td>Income Tax (PAYE)</td><td align="right">-${{ number_format($payslip->income_tax, 2) }}</td></tr>
                    <tr><td>Pension Fund (8%)</td><td align="right">-${{ number_format($payslip->pension, 2) }}</td></tr>
                    <tr><td>Social Security (5%)</td><td align="right">-${{ number_format($payslip->social_security, 2) }}</td></tr>
                    <tr><td>Medical Aid (3%)</td><td align="right">-${{ number_format($payslip->medical_aid, 2) }}</td></tr>
                    <tr style="font-weight: bold; background: #f8fafc;">
                        <td>TOTAL DEDUCTIONS</td>
                        <td align="right">-${{ number_format($payslip->total_deductions, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="total-box">
        <table width="100%">
            <tr>
                <td>
                    <strong style="color: #065f46;">NET PAYABLE AMOUNT:</strong><br>
                    <span style="font-size: 11px; color: #047857;">Payment Direct Deposited</span>
                </td>
                <td align="right" class="amount">
                    ${{ number_format($payslip->net_pay, 2) }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
