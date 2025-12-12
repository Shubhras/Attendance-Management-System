@extends('layout.layout')
@php
$title = 'Attendance';
$subTitle = 'Manage Attendance';
@endphp

@section('content')
<div class="card h-100 p-0 radius-12">
<!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Validation Error!</strong> Please check the form below.
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>

            <!-- Filter Form -->
            <!-- <form method="GET" action="{{ route('attendance.index') }}" class="d-flex gap-2">

                <select name="range" class="form-select form-select-sm w-auto h-40-px" onchange="this.form.submit()">
                    <option value="">Filter Range</option>
                    <option value="daily" {{ request('range')=='daily'?'selected':'' }}>Daily</option>
                    <option value="monthly" {{ request('range')=='monthly'?'selected':'' }}>Monthly</option>
                    <option value="3months" {{ request('range')=='3months'?'selected':'' }}>Last 3 Months</option>
                    <option value="6months" {{ request('range')=='6months'?'selected':'' }}>Last 6 Months</option>
                </select>

            </form> -->
            <form method="GET" action="{{ route('attendance.index') }}"
                class="d-flex align-items-center gap-3 flex-wrap" style="width:100%;">

                <!-- Date -->
                <input type="date" name="date" value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                    class="form-control h-40-px" style="max-width: 180px;" />

                <!-- Range Filter -->
                <select name="range" class="form-select form-select-sm h-40-px" style="max-width:150px;">
                    <option value="">Select Range</option>
                    <option value="daily" {{ request('range')=='daily'?'selected':'' }}>Daily</option>
                    <option value="monthly" {{ request('range')=='monthly'?'selected':'' }}>Monthly</option>
                    <option value="3months" {{ request('range')=='3months'?'selected':'' }}>Last 3 Months</option>
                    <option value="6months" {{ request('range')=='6months'?'selected':'' }}>Last 6 Months</option>
                </select>

                <!-- Search -->
<div class="navbar-search" style="flex:1; min-width:220px;">
    <input type="text" class="bg-base h-40-px w-100" 
           name="search" 
           value="{{ request('search') }}"
           placeholder="Search name or code">
</div>

<button type="submit" class="btn btn-primary btn-sm px-16 py-12 radius-8">
    Apply
</button>


            </form>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('attendance.exportAll', [
    'date' => request('date'),
    'range' => request('range')
]) }}" class="btn btn-outline-primary btn-sm px-12 py-12 radius-8">
                <iconify-icon icon="mdi:download"></iconify-icon> Download PDF
            </a>

        </div>
    </div>

    <div class="card-body p-24">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="bulkAttendanceForm" action="{{ route('attendance.saveBulk') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}">

            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Emp Code</th>
                            <th>Machine</th>
                            <th>Shift Type</th>
                            <th>Shift 1</th>
                            <th>Shift 2</th>
                            <th>Shift 3</th>
                            <th>Marked By</th>
                            <th>Shift</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                  <tbody>
@forelse($employees as $index => $emp)

    @php
        // Get all attendances of employee
        $attList = $attendanceMap[$emp->id] ?? collect();

        // Pick MORNING first, if exists. Otherwise NIGHT.
        $att = $attList->firstWhere('shift_type', 'morning')
            ?? $attList->first();

        $status = $att?->status ?? null;
        $clock_in = $att?->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('H:i') : '';
        $clock_out = $att?->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('H:i') : '';

        $slot1 = $att?->slot1;
        $slot2 = $att?->slot2;
        $slot3 = $att?->slot3;

        $shiftType = $att?->shift_type;
        $markerName = $att?->marker?->name ?? '-';
    @endphp

    <tr>
        <td>{{ $employees->firstItem() + $index }}</td>
        <td>{{ $emp->name }}</td>
        <td>{{ $emp->employee_code }}</td>
        <td>{{ $emp->machineRelation?->name ?? 'N/A' }}</td>
        <td>{{ $shiftType }}</td>
        <td>{{ $slot1 }}</td>
        <td>{{ $slot2 }}</td>
        <td>{{ $slot3 }}</td>
        <td>{{ $markerName }}</td>
        <td>{{ $emp->shift?->shift_name ?? '-' }}</td>
        <td>{{ $emp->mobile }}</td>

        <td>
            <input type="hidden" name="records[{{ $index }}][employee_id]" value="{{ $emp->id }}">
            <input type="hidden" name="records[{{ $index }}][machine_id]" value="{{ $emp->machine?->id ?? '' }}">
            
            <select name="records[{{ $index }}][status]" class="form-select form-select-sm">
                <option value="1" {{ $status == 1 ? 'selected' : '' }}>Present</option>
                <option value="0" {{ $status == 0 ? 'selected' : '' }}>Leave</option>
                <option value="2" {{ $status == 2 ? 'selected' : '' }}>Half day</option>
            </select>
        </td>

        <td><input type="time" name="records[{{ $index }}][clock_in]" class="form-control form-control-sm" value="{{ $clock_in }}"></td>
        <td><input type="time" name="records[{{ $index }}][clock_out]" class="form-control form-control-sm" value="{{ $clock_out }}"></td>

        <td class="text-center">
            @if($att)
                <a href="{{ route('attendance.edit', $att->id) }}" class="btn btn-sm btn-light">
                    <iconify-icon icon="mdi:eye"></iconify-icon>
                </a>
            @else
                <a href="{{ route('attendance.singleMark', ['employee_id' => $emp->id, 'date' => request('date')]) }}"
                   class="btn btn-sm btn-primary">Mark</a>
            @endif
        </td>
    </tr>

@empty
<tr>
    <td colspan="15" class="text-center">No employees found.</td>
</tr>
@endforelse
</tbody>

                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                <span>Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of
                    {{ $employees->total() }} entries</span>
                <div>
                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8">Save All</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // nothing fancy here — form submits normally
    // optionally you can add a confirm dialog
    document.getElementById('bulkAttendanceForm').addEventListener('submit', function(e) {
        if (!confirm('Save attendance for all rows?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection