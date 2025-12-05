<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Employee Attendance Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 15px;
            background: #fff;
            color: #333;
            font-size: 11px;
        }

        .header-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .generated-at {
            text-align: center;
            font-size: 10px;
            margin-bottom: 8px;
            color: #555;
        }

        .section-box {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 6px;
            border-bottom: 1px solid #3498db;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto; /* ⭐ AUTO ADJUST — Prevent breaking */
        }

        table th,
        table td {
            border: 1px solid #bbb;
            padding: 5px;
            text-align: center;
            font-size: 10px;
            word-wrap: break-word;
        }

        table th {
            background: #3498db;
            color: #fff;
        }

        /* ⭐ FIX: Prevent header text from stacking vertically */
        th div {
            writing-mode: horizontal-tb;
        }

        .no-data {
            text-align: center;
            font-size: 12px;
            padding: 10px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="header-title">Employee Attendance Report</div>

    <div class="generated-at">Generated At: {{ $generated_at }}</div>
    <div class="generated-at">Period: {{ $startDate }} to {{ $endDate }}</div>

    <div class="section-box">
        <div class="section-title">Attendance + Salary Summary</div>

        @if(count($reportData) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Emp Code</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Employee Type</th>
                    <th>Contractor</th>
                    <th>Total Days</th>
                    <th>Present</th>
                    <th>Leave</th>
                    <th>Half Day</th>
                    <th>Per Day Pay</th>
                    <th>Present Salary</th>
                    <th>Half Day Salary</th>
                    <th>Total Salary</th>
                </tr>
            </thead>

            <tbody>
                @foreach($reportData as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row['employee']->employee_code }}</td>
                    <td>{{ $row['employee']->name }}</td>
                    <td>{{ $row['employee']->mobile }}</td>
                    <td>{{ $row['type'] }}</td>
                    <td>{{ $row['contractor_name'] }}</td>
                    <td>{{ $row['total_days'] }}</td>
                    <td>{{ $row['present'] }}</td>
                    <td>{{ $row['leave'] }}</td>
                    <td>{{ $row['half_day'] }}</td>
                    <td>{{ number_format($row['per_day_pay'],2) }}</td>
                    <td>{{ number_format($row['salary_present'],2) }}</td>
                    <td>{{ number_format($row['salary_half'],2) }}</td>
                    <td>{{ number_format($row['total_salary'],2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">No employee data found.</div>
        @endif

    </div>

</body>

</html>
