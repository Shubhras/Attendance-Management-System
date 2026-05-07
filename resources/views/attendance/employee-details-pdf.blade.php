<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Employee Attendance Report</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 11px;
        }

        th {
            background: #f0f3f6;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

    </style>

</head>

<body>

<div class="header">

    <h2>Employee Attendance Details</h2>

    <div>Date: {{ $date }}</div>

    <div>Range: {{ ucfirst($range) }}</div>

    <div>Generated: {{ $generatedAt }}</div>

</div>

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

            <td>{{ $a->employee->contractor->name ?? '-' }}</td>

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