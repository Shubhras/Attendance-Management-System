<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report - {{ $date }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:13px; }
        .header{ text-align:center; margin-bottom:10px; }
        table{ width:100%; border-collapse:collapse; }
        th, td { border:1px solid #ddd; padding:6px; font-size:12px; }
        th { background:#f0f3f6; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Attendance Management System</h2>
        <div>Report Date: {{ $date }}</div>
        <div>Generated: {{ $generatedAt }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Emp Code</th>
                <th>Clock In</th>
                <th>Clock Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendance as $i => $a)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $a->employee->name }}</td>
                <td>{{ $a->employee->employee_code }}</td>
                <td>{{ $a->clock_in ?? '-' }}</td>
                <td>{{ $a->clock_out ?? '-' }}</td>
                <td>{{ ucfirst($a->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
