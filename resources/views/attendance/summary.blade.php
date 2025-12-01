@extends('layout.layout')

@section('content')

<style>
.summary-table {
    width: 100%;
    min-width: 1200px;
}

.summary-table th,
.summary-table td {
    padding: 6px 8px;
    text-align: center;
    border: 1px solid #ddd;
    vertical-align: middle;
}

/* Hover effect on attendance cells */
.summary-table td:hover {
    background: #f0f8ff;
    cursor: pointer;
    transition: 0.2s;
}

.emp-name {
    text-align: left !important;
    white-space: nowrap;
}

.status-icon {
    font-size: 18px;
    font-weight: bold;
}

/* Present */
.text-present {
    color: #28a745;
}

/* Absent */
.text-absent {
    color: #dc3545;
}

/* Half Day */
.text-half {
    color: #ff9800;
}

/* Leave (❌) */
.text-leave {
    color: #3f51b5;
}

.scroll-x {
    overflow-x: auto;
    white-space: nowrap;
}
</style>

<div class="card p-3">
    <h3 class="mb-3">Attendance Summary</h3>

    <form method="GET" action="">
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="month" name="month" class="form-control"
                       value="{{ $month }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="scroll-x">
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Employee</th>

                    @for($d = 1; $d <= $daysInMonth; $d++)
                        <th>{{ str_pad($d,2,'0',STR_PAD_LEFT) }}</th>
                    @endfor
                </tr>
            </thead>

            <tbody>
                @foreach($employees as $emp)
                    <tr>
                        <td class="emp-name">
                            <strong>{{ $emp->name }}</strong><br>
                            <small>{{ $emp->employee_code }}</small>
                        </td>

                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $day = str_pad($d,2,'0',STR_PAD_LEFT);
                                $status = $attendanceMap[$emp->id][$day] ?? null;
                            @endphp

                            <td>
                                @if($status === 'present')
                                    <span class="status-icon text-present">✔</span>

                                @elseif($status === 'half_day')
                                    <span class="status-icon text-half">½</span>

                                @elseif($status === 'leave')
                                    <span class="status-icon text-leave">❌</span>

                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
