<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Machine Summary Report</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 10px;
            vertical-align: top;
        }

        th {
            background: #f0f3f6;
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

    </style>

</head>

<body>

    <div class="header">

        <h2>Machine Wise Attendance Summary</h2>

        <div>
            <strong>Date:</strong> {{ $date }}
        </div>

        <div>
            <strong>Range:</strong> {{ $range }}
        </div>

        <div>
            <strong>Generated:</strong> {{ $generatedAt }}
        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th>#</th>

                <th>Machine</th>

                <th>Total Employee</th>

                <th>Attendance</th>

                <th>Male</th>

                <th>Female</th>

                <th>Company Employee</th>

                <th>Company Employee Names</th>

                <th>Contractor Employee</th>

                <th>Contractor Details</th>

            </tr>

        </thead>

        <tbody>

            @forelse($machineSummary as $index => $m)

                <tr>

                    <td>{{ $index + 1 }}</td>

                    <td class="text-left">
                        {{ $m['machine_name'] }}
                    </td>

                    <td>{{ $m['total_employee'] }}</td>

                    <td>{{ $m['attendance_count'] }}</td>

                    <td>{{ $m['male_count'] }}</td>

                    <td>{{ $m['female_count'] }}</td>

                    <td>{{ $m['company_employee'] }}</td>

                    <td class="text-left">
                        {{ $m['company_employee_names'] }}
                    </td>

                    <td>{{ $m['contractor_employee'] }}</td>

                    <td class="text-left">
                        {{ $m['contractor_details'] }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="10">
                        No Record Found
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</body>

</html>