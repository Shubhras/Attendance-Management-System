<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Salary Slip</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .header { text-align:center; margin-bottom:10px; }
    .company { font-size:16px; font-weight:700; }
    .table { width:100%; border-collapse: collapse; margin-top:10px; }
    .table th, .table td { padding:8px; border:1px solid #ddd; }
    .right { text-align:right; }
  </style>
</head>
<body>
  <div class="header">
    <div class="company">Attendance Management System</div>
    <div>Salary Slip - {{ $payment->month }}</div>
    <div>Generated at: {{ $generatedAt }}</div>
  </div>

  <div>
    <strong>Employee:</strong> {{ $payment->employee->name }} ({{ $payment->employee->employee_code }})<br/>
    <strong>Department:</strong> {{ $payment->employee->company_department ?? '-' }}<br/>
    <strong>Payment Date:</strong> {{ $payment->date_paid }}
        <strong>Paid By:</strong>
    @if($payment->creator)
        {{ $payment->creator->name }} ({{ ucfirst($payment->creator->role) }})
    @else
        —
    @endif
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>Description</th>
        <th class="right">Amount (INR)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($payment->items as $it)
      <tr>
        <td>{{ $it->title }}</td>
        <td class="right">{{ number_format($it->amount,2) }}</td>
      </tr>
      @endforeach
      <tr>
        <td><strong>Gross</strong></td>
        <td class="right"><strong>{{ number_format($payment->gross_amount,2) }}</strong></td>
      </tr>
      <tr>
        <td> Deductions </td>
        <td class="right">{{ number_format($payment->deductions,2) }}</td>
      </tr>
      <tr>
        <td><strong>Net Pay</strong></td>
        <td class="right"><strong>{{ number_format($payment->net_amount,2) }}</strong></td>
      </tr>
    </tbody>
  </table>

  <div style="margin-top:20px;">Authorized Signature: ____________________</div>
</body>
</html>
