@extends('layout.layout')
@php $title='Salary'; $subTitle='Salary Payments'; @endphp

@section('content')
<div class="card radius-12 p-24">
    <div class="d-flex justify-content-between mb-3">
        <h5>Salary Payments</h5>
        <div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calculateModal">Calculate & Pay</a>
            <a href="{{ route('salary.report.monthly', ['month' => request('month', \Carbon\Carbon::now()->format('Y-m'))]) }}" class="btn btn-outline-primary">Download Monthly Report</a>
        </div>
    </div>

    <form method="GET" class="mb-3">
        <div class="d-flex gap-2">
            <input type="month" name="month" value="{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}" class="form-control" />
            <select name="employee_id" class="form-select">
                <option value="">All Employees</option>
                @foreach(\App\Models\Employee::orderBy('name')->get() as $e)
                    <option value="{{ $e->id }}" {{ request('employee_id')==$e->id ? 'selected':'' }}>{{ $e->name }} ({{ $e->employee_code }})</option>
                @endforeach
            </select>
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table bordered-table">
            <thead>
                <tr><th>#</th><th>Employee</th><th>Month</th><th>Gross</th><th>Net</th><th>Date Paid</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                <tr>
                    <td>{{ $payments->firstItem() + $loop->index }}</td>
                    <td>{{ $p->employee->name }} ({{ $p->employee->employee_code }})</td>
                    <td>{{ $p->month }}</td>
                    <td>{{ number_format($p->gross_amount,2) }}</td>
                    <td>{{ number_format($p->net_amount,2) }}</td>
                    <td>{{ $p->date_paid }}</td>
                    <td>
                        <a href="{{ route('salary.slipPdf', $p->id) }}" class="btn btn-sm btn-outline-primary">Slip PDF</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-between">
        <div>Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }}</div>
        <div>{{ $payments->links() }}</div>
    </div>
</div>

<!-- Calculate & Pay Modal -->
<div class="modal fade" id="calculateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content radius-12">
      <div class="modal-header">
        <h5 class="modal-title">Calculate Salary</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
         <form id="calcForm">
             @csrf
             <div class="row g-3">
                 <div class="col-md-6">
                     <label class="form-label">Employee</label>
                     <select name="employee_id" id="employee_id" class="form-select" required>
                         <option value="">Select</option>
                         @foreach(\App\Models\Employee::orderBy('name')->get() as $e)
                             <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->employee_code }})</option>
                         @endforeach
                     </select>
                 </div>
                 <div class="col-md-6">
                     <label class="form-label">Month</label>
                     <input type="month" name="month" id="month" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m') }}" required />
                 </div>

                 <div class="col-12">
                     <button type="button" id="btnCalculate" class="btn btn-primary">Calculate</button>
                 </div>

                 <div class="col-12 mt-3" id="calcResult"></div>

                 <div class="col-12 text-end mt-3">
                     <button type="button" id="btnPay" class="btn btn-success d-none">Pay & Save</button>
                 </div>
             </div>
         </form>
      </div>
    </div>
  </div>
</div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function() {
    const calcBtn = document.getElementById('btnCalculate');
    const payBtn = document.getElementById('btnPay');
    const calcResult = document.getElementById('calcResult');

    calcBtn.addEventListener('click', async () => {
        const employee_id = document.getElementById('employee_id').value;
        const month = document.getElementById('month').value;
        if(!employee_id || !month) { alert('Select employee & month'); return; }
        calcResult.innerHTML = 'Calculating...';

        const res = await fetch("{{ route('salary.calculate') }}", {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({ employee_id, month })
        });
        const data = await res.json();
        if(!data.status) { calcResult.innerHTML = 'Error'; return; }
        const d = data.data;
        calcResult.innerHTML = `
            <div>
                <strong>Total Days:</strong> ${d.total_days} <br/>
                <strong>Present:</strong> ${d.present} <br/>
                <strong>Half Days:</strong> ${d.half_day} <br/>
                <strong>Leave:</strong> ${d.leave} <br/>
                <strong>Absent:</strong> ${d.absent} <br/>
                <strong>Gross:</strong> ${d.gross} <br/>
                <strong>Net:</strong> ${d.net}
            </div>
        `;

        payBtn.dataset.employee = employee_id;
        payBtn.dataset.month = month;
        payBtn.classList.remove('d-none');
    });

    payBtn.addEventListener('click', async () => {
        if(!confirm('Pay salary now?')) return;
        const employee_id = payBtn.dataset.employee;
        const month = payBtn.dataset.month;

        const res = await fetch("{{ route('salary.pay') }}", {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({ employee_id, month })
        });
        const data = await res.json();
        if(data.status) {
            alert('Paid successfully');
            window.location.reload();
        } else {
            alert('Error: '+(data.message||''));
        }
    });
});
</script>
