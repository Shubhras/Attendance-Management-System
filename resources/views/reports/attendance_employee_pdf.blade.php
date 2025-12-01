<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Attendance Report - {{ $employee->name }}</title>
<style>
/* Use same clean styles as earlier — keeps DejaVu Sans for DomPDF */
body{font-family:DejaVu Sans, sans-serif; font-size:12px;}
.header{ text-align:center; margin-bottom:10px;}
.table{width:100%;border-collapse:collapse;}
.table th, .table td{border:1px solid #ccc;padding:6px;text-align:left;}
.table th{background:#f0f0f0;}
.footer{margin-top:12px;font-size:11px;}
</style>
</head>
<body>
<div class="header">
  <h2>Attendance Management System</h2>
  <div>Employee: {{ $employee->name }} ({{ $employee->employee_code }})</div>
  <div>Period: {{ $start }} to {{ $end }}</div>
  <div>Generated: {{ $generated_at }}</div>
</div>

<table class="table">
<thead>
<tr><th>#</th><th>Date</th><th>Clock In</th><th>Clock Out</th><th>Status</th></tr>
</thead>
<tbody>
@foreach($records as $i=>$r)
<tr>
  <td>{{ $i+1 }}</td>
  <td>{{ $r->date->format('Y-m-d') }}</td>
  <td>{{ $r->clock_in ?? '-' }}</td>
  <td>{{ $r->clock_out ?? '-' }}</td>
  <td>{{ ucfirst($r->status) }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="footer">Generated at: {{ $generated_at }}</div>
</body>
</html>
