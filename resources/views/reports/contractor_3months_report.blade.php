<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Contractors - Last 3 Months Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 15px; }
        .header { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .generated { text-align: center; font-size: 9px; color: #555; margin-bottom: 20px; }
        .contractor { margin-bottom: 30px; page-break-inside: avoid; }
        .c-name { font-size: 14px; font-weight: bold; background: #2c3e50; color: white; padding: 8px; }
        .month { font-size: 12px; font-weight: bold; background: #3498db; color: white; padding: 5px; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 9px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background: #f0f0f0; }
        .text-left { text-align: left; }
    </style>
</head>
<body>

<div class="header">All Contractors - Last 3 Months Salary & Attendance Report</div>
<div class="generated">Generated on: {{ $generated_at }}</div>

@foreach($allData as $data)
    <div class="contractor">
        <div class="c-name">{{ $data['contractor']->name }} ({{ $data['contractor']->mobile }}) - {{ $data['month']['name'] }}</div>

        @if(count($data['summary']) > 0)
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Present</th>
                        <th>Leave</th>
                        <th>Half</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['summary'] as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row['employee']->employee_code }}</td>
                            <td class="text-left">{{ $row['employee']->name }}</td>
                            <td>{{ $row['present'] }}</td>
                            <td>{{ $row['leave'] }}</td>
                            <td>{{ $row['half_day'] }}</td>
                            <td>{{ number_format($row['total_salary'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align:center;color:#999;font-style:italic;">No attendance data</p>
        @endif
    </div>
@endforeach

</body>
</html>