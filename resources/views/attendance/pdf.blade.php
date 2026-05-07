<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report - {{ $date }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 11px;
            vertical-align: top;
        }

        th {
            background: #f0f3f6;
        }

        h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>Attendance Management System</h2>

    <div>
        Report Date: {{ $date }}
    </div>

    <div>
        Generated: {{ $generatedAt }}
    </div>
</div>

<!-- MACHINE SUMMARY -->

<h3>Machine Wise Summary</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Machine</th>
            <th>Total Employee</th>
            <th>Attendance</th>
            <th>Male</th>
            <th>Female</th>
            <th>Company</th>
            <th>Contractor</th>
            <th>Contractor Name</th>
        </tr>
    </thead>

    <tbody>
        @foreach($machineSummary as $i => $m)
        <tr>
            <td>{{ $i + 1 }}</td>

            <td>{{ $m['machine_name'] }}</td>

            <td>{{ $m['total_employee'] }}</td>

            <td>{{ $m['attendance_count'] }}</td>

            <td>{{ $m['male_count'] }}</td>

            <td>{{ $m['female_count'] }}</td>

            <td>{{ $m['company_employee'] }}</td>

            <td>{{ $m['contractor_employee'] }}</td>

            <td>{{ $m['contractors'] ?: '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- ATTENDANCE DETAILS -->

<h3>Employee Attendance Details</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Emp Code</th>
            <th>Machine</th>
            <th>Gender</th>
            <th>Employee Type</th>
            <th>Contractor</th>
            <th>Clock In</th>
            <th>Clock Out</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

        @foreach($attendance as $i => $a)

        <tr>

            <td>{{ $i + 1 }}</td>

            <td>{{ $a->employee->name ?? '-' }}</td>

            <td>{{ $a->employee->employee_code ?? '-' }}</td>

            <td>{{ $a->machine->name ?? '-' }}</td>

            <td>{{ ucfirst($a->employee->gender ?? '-') }}</td>

            <td>{{ ucfirst($a->employee->employee_type ?? '-') }}</td>

            <td>
                {{ $a->employee->contractor->name ?? '-' }}
            </td>

            <td>{{ $a->clock_in ?? '-' }}</td>

            <td>{{ $a->clock_out ?? '-' }}</td>

            <td>
                @if($a->status == 1)
                    Present
                @elseif($a->status == 0)
                    Leave
                @elseif($a->status == 2)
                    Half Day
                @else
                    -
                @endif
            </td>

        </tr>

        @endforeach

    </tbody>
</table>

</body>
</html>