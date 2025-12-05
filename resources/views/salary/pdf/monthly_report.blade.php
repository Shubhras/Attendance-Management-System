<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Salary Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Monthly Salary Report</h2>
        <p>{{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Employee Code</th>
                <th>Total Days</th>
                <th>Present</th>
                <th>Half Day</th>
                <th>Leave</th>
                <th>Total Salary</th>
            </tr>
        </thead>

        <tbody>
            @php 
                $grandTotal = 0; 
                $totalDays = \Carbon\Carbon::parse($month.'-01')->daysInMonth;
            @endphp

            @foreach($payments as $index => $payment)

                @php
                    $grandTotal += $payment->total_salary;
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payment->employee->name ?? 'N/A' }}</td>
                    <td>{{ $payment->employee->employee_code ?? 'N/A' }}</td>
                    <td>{{ $totalDays }}</td>
                    <td>{{ $payment->present_days }}</td>
                    <td>{{ $payment->half_days }}</td>
                    <td>{{ $payment->leave_days }}</td>
                    <td>{{ number_format($payment->total_salary, 2) }}</td>
                </tr>

            @endforeach
        </tbody>

        <tfoot>
            <tr class="totals">
                <td colspan="7" class="text-right">Grand Total</td>
                <td>{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>

</body>
</html>
