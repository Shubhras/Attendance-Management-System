<!DOCTYPE html>
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

        <!-- Contractor Details -->
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

        <!-- Employees Table -->
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
</html>
