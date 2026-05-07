<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Attendance Report</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .summary-box {
            margin-bottom: 20px;
        }

        .summary-box table {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 11px;
        }

        th {
            background: #f0f3f6;
        }

        h3 {
            margin-top: 20px;
        }

    </style>

</head>

<body>

<div class="header">

    <h2>Attendance Management System</h2>

    <div>
        Report Range:
        <strong>{{ ucfirst($range) }}</strong>
    </div>

    <div>
        Report Date:
        <strong>{{ $date }}</strong>
    </div>

    <div>
        Generated:
        <strong>{{ $generatedAt }}</strong>
    </div>

</div>

<!-- SUMMARY -->

<h3>Attendance Summary</h3>

<div class="summary-box">

    <table>

        <tr>
            <th>Total Employee</th>
            <td>{{ $totalEmployee }}</td>

            <th>Total Attendance</th>
            <td>{{ $totalAttendance }}</td>
        </tr>

        <tr>
            <th>Total Male</th>
            <td>{{ $maleCount }}</td>

            <th>Total Female</th>
            <td>{{ $femaleCount }}</td>
        </tr>

        <tr>
            <th>Company Employee</th>
            <td>{{ $companyEmployee }}</td>

            <th>Contractor Employee</th>
            <td>{{ $contractorEmployee }}</td>
        </tr>

        <tr>
            <th>Contractor Name</th>
            <td colspan="3">
                {{ $contractorNames ?: '-' }}
            </td>
        </tr>

    </table>

</div>

<!-- DETAILS -->

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