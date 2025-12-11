{{--@extends('layout.layout')

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
        <div class="col-md-3">
    <select name="employee_id" class="form-control">
        <option value="">All Employees</option>
        @foreach($employees as $emp)
            <option value="{{ $emp->id }}" 
                {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                {{ $emp->name }} ({{ $emp->employee_code }})
            </option>
        @endforeach
    </select>
</div>
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
            </thead>--}}

            <!-- <tbody>
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
            </tbody> -->
       {{--     <tbody>
    @foreach($employees as $emp)
        <tr>
            <td class="emp-name">
                <strong>{{ $emp->name }}</strong><br>
                <small>{{ $emp->employee_code }}</small>
            </td>

            @for($d = 1; $d <= $daysInMonth; $d++)
                @php
                    // GET STATUS AS INT OR NULL
                    $status = $attendanceMap[$emp->id][$d] ?? null;
                @endphp

                <td>
                    @if($status === 1)
                        <!-- Present -->
                        <span class="status-icon text-present">✔</span>

                    @elseif($status === 0)
                        <!-- Absent -->
                        <span class="status-icon text-absent">✘</span>

                    @elseif($status === 2)
                        <!-- Half Day (Optional future use) -->
                        <span class="status-icon text-half">½</span>

                    @elseif($status === 3)
                        <!-- Leave (Optional) -->
                        <span class="status-icon text-leave">❌</span>

                    @else
                        <!-- No entry -->
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

@endsection--}}
@extends('layout.layout')

@section('content')

<style>
.summary-table {
    width: 100%;
    min-width: 1400px;
}

.summary-table th,
.summary-table td {
    padding: 6px 8px;
    text-align: center;
    border: 1px solid #ddd;
    vertical-align: middle;
}

.summary-table td:hover {
    background: #f0f8ff;
    cursor: pointer;
}

.emp-name {
    text-align: left !important;
}

.status-icon {
    font-size: 18px;
    font-weight: bold;
}

.text-present { color: #28a745; }
.text-absent  { color: #dc3545; }
.text-half    { color: #ff9800; }
.text-leave   { color: #3f51b5; }

.scroll-x {
    overflow-x: auto;
    white-space: nowrap;
}
</style>

<div class="card p-3">
    <h3 class="mb-3">Attendance Summary</h3>

    <!-- FILTER FORM -->
    <form method="GET">
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Search by Employee Code</label>
                <input type="text" name="search_code" class="form-control" placeholder="Enter EMP-03" value="{{ request('search_code') }}">
            </div>
            <div class="col-md-3">
                <label>Employee</label>
                <select name="employee_id" class="form-control">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} ({{ $emp->employee_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Select Month</label>
                <input type="month" name="month" class="form-control" value="{{ $month }}">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

        </div>
    </form>

    <div class="scroll-x">
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        <th>{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}</th>
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
                                $info   = $attendanceMap[$emp->id][$d] ?? null;
                                $status = $info['status'] ?? null;
                                $hours  = $info['hours'] ?? null;
                            @endphp

                            <td>
                                {{-- PRESENT --}}
                                @if($status === 1)
                                    <span class="status-icon text-present">✔</span>
                                    @if($hours)
                                        <div style="font-size:11px">{{ $hours }}</div>
                                    @endif

                                {{-- ABSENT --}}
                                @elseif($status === 0)
                                    <span class="status-icon text-absent">✘</span>

                                {{-- HALF DAY --}}
                                @elseif($status === 2)
                                    <span class="status-icon text-half">½</span>
                                    @if($hours)
                                        <div style="font-size:11px">{{ $hours }}</div>
                                    @endif

                                {{-- LEAVE --}}
                                @elseif($status === 3)
                                    <span class="status-icon text-leave">❌</span>

                                {{-- NO ENTRY --}}
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

