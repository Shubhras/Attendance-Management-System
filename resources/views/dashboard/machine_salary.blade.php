@extends('layout.layout')
@php
$title = 'Machine Salary Calculation';
$subTitle = 'Machine-wise Employee & Salary Summary';
@endphp

@section('content')
<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <h4 class="mb-0 fw-bold">Machine-wise Salary Calculation</h4>
        </div>

        <!-- Search & Per Page -->
        <form method="GET" action="{{ route('dashboard.machine-salary') }}" class="d-flex align-items-center gap-2">
            <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>

            <div class="navbar-search">
                <input type="text" class="bg-base h-40-px w-auto" name="search" value="{{ request('search') }}"
                       placeholder="Search machine name">
                <button class="btn btn-primary" type="submit">
                    submit
                </button>
            </div>
        </form>
        <form method="GET" action="{{ route('dashboard.machine-consumption.export') }}" class="d-flex gap-2">
    <input type="date" name="from_date" class="form-control" required>
    <input type="date" name="to_date" class="form-control" required>
    <button type="submit" class="btn btn-success">
        Export Excel
    </button>
</form>
    </div>

    <!-- Table -->
    <div class="card-body p-24">
        <div class="table-responsive">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Machine Name</th>
                        <th>Machine Photo</th>
                        <th>Total Employees</th>
                        <th>Total Daily Cost</th>
                        <th>Total Monthly Cost</th>
                        <th>Avg Salary / Employee</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($machineStats as $index => $stat)
                    <tr>
                        <td>{{ $machineStats->firstItem() + $index }}</td>
                        <td><strong>{{ $stat->machine_name }}</strong></td>
                        <td>
                            @if($stat->machine_image && file_exists(public_path('machines_image/photos/' . basename($stat->machine_image))))
                                <img src="{{ asset('machines_image/photos/' . basename($stat->machine_image)) }}"
                                     class="rounded" width="60" height="60" style="object-fit: cover;">
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;">
                                    <iconify-icon icon="fa6-solid:industry" class="text-muted fs-4"></iconify-icon>
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary fs-14 px-3 py-2">{{ $stat->total_employees }}</span>
                        </td>
                        <td style="color:red;">
                            ₹{{ number_format($stat->total_daily_salary, 2) }}
                        </td>
                        <td class="text-success">
                            ₹{{ number_format($stat->total_monthly_salary, 2) }}
                        </td>
                        <td class="text-info">
                            @if($stat->total_employees > 0)
                                ₹{{ number_format($stat->total_monthly_salary / $stat->total_employees, 2) }}
                            @else
                                ₹0.00
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <iconify-icon icon="fa6-solid:tools" class="fs-1 mb-3 d-block"></iconify-icon>
                            No machines found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-4">
            <span>Showing {{ $machineStats->firstItem() ?? 0 }} to {{ $machineStats->lastItem() ?? 0 }} of {{ $machineStats->total() }} machines</span>
            <div>{{ $machineStats->links() }}</div>
        </div>
    </div>
</div>
@endsection