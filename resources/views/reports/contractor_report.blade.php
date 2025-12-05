<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contractor Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f7f9fc;
            color: #333;
        }

        .header-title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .generated-at {
            text-align: center;
            font-size: 12px;
            margin-bottom: 25px;
            color: #555;
        }

        .contractor-box {
            background: #ffffff;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .contractor-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #3498db;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table th {
            background: #3498db;
            color: white;
            padding: 8px;
            font-size: 13px;
            text-align: left;
        }

        table td {
            background: #f9fbfd;
            padding: 8px;
            font-size: 13px;
            border-bottom: 1px solid #e6eff5;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 28%;
        }

        .employee-section-title {
            font-size: 16px;
            margin-top: 12px;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }

        .no-data {
            text-align: center;
            padding: 10px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header-title">Contractor Report</div>
    <div class="generated-at">Generated At: {{ $generated_at }}</div>

    <div class="contractor-box">

        <div class="contractor-title">
            {{ $contractor->name }} ({{ $contractor->mobile }})
        </div>

       
        <table class="info-table">
            <tr>
                <td>Contractor Name</td>
                <td>{{ $contractor->name }}</td>
            </tr>
            <tr>
                <td>Mobile</td>
                <td>{{ $contractor->mobile }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $contractor->email }}</td>
            </tr>
            <tr>
                <td>Company Name</td>
                <td>{{ $contractor->company_name }}</td>
            </tr>
            <tr>
                <td>Total Employees</td>
                <td>{{ $contractor->employees->count() }}</td>
            </tr>
        </table>

        <div class="employee-section-title">Employees</div>

        @if($contractor->employees->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Code</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Gender</th>
                        <th>Shift</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($contractor->employees as $index => $emp)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $emp->employee_code }}</td>
                            <td>{{ $emp->name }}</td>
                            <td>{{ $emp->mobile }}</td>
                            <td>{{ $emp->gender }}</td>
                            <td>{{ $emp->shift->shift_name ?? '--' }}</td>
                            <td>{{ $emp->shift->clock_in_time ?? '--' }}</td>
                            <td>{{ $emp->shift->clock_out_time ?? '--' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @else
            <div class="no-data">No employees under this contractor.</div>
        @endif

    </div>

</body>
</html> -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Contractor Report</title>

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
            table-layout: auto;
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

        .info-table td:first-child {
            font-weight: bold;
            text-align: left;
            width: 28%;
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

    <div class="header-title">Contractor Report</div>
    <div class="generated-at">Generated At: {{ $generated_at }}</div>

    <!-- Contractor Details -->
    <div class="section-box">
        <div class="section-title">Contractor Details</div>

        <table class="info-table">
            <tr>
                <td>Contractor Name</td>
                <td>{{ $contractor->name }}</td>
            </tr>
            <tr>
                <td>Mobile</td>
                <td>{{ $contractor->mobile }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $contractor->email }}</td>
            </tr>
            <tr>
                <td>Company Name</td>
                <td>{{ $contractor->company_name }}</td>
            </tr>
            <tr>
                <td>Total Employees</td>
                <td>{{ $contractor->employees->count() }}</td>
            </tr>
        </table>
    </div>

    <!-- Attendance + Salary Summary -->
    <div class="section-box">
        <div class="section-title">Employee Details + Attendance + Salary Summary</div>

        @if(count($summary) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Emp Code</th>
                    <th>Name</th>
                    <th>Employee Type</th>
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
                @foreach ($summary as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row['employee']->employee_code }}</td>
                    <td>{{ $row['employee']->name }}</td>
                    <td>{{ $row['employee']->employee_type ?? '--' }}</td>
                    <td>{{ $row['month_days'] }}</td>
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
        <div class="no-data">No attendance data found.</div>
        @endif

    </div>

</body>

</html>
