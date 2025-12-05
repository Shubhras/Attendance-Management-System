@extends('layout.layout')
@php
$title = 'Shifts';
$subTitle = 'Manage Shifts';
@endphp

@section('content')
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
<div class="card h-100 p-0 radius-12">

    <!-- Header -->
    <div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">

        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>

            <!-- Filter -->
            <form method="GET" action="{{ route('shifts.index') }}" class="d-flex align-items-center gap-2">
                <!-- Per page -->
                <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px"
                    onchange="this.form.submit()">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>

                <!-- Search -->
                <div class="navbar-search">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" value="{{ request('search') }}"
                        placeholder="Search shift name">
                    <button class="btn" type="submit" style="border:none;background:transparent;">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </button>
                </div>
            </form>
        </div>

        <!-- Add Button -->
        <button type="button"
            class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#createShiftModal">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Add Shift
        </button>

    </div>

    <!-- Table -->
    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Created</th>
                        <th>Shift Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Hours</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($shifts as $index => $shift)
                    <tr>
                        <td>{{ $shifts->firstItem() + $index }}</td>
                        <td>{{ $shift->created_at?->format('d M Y') }}</td>
                        <td>{{ $shift->shift_name }}</td>
                        <td>{{ $shift->clock_in_time }}</td>
                        <td>{{ $shift->clock_out_time }}</td>
                        <td>22</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('shifts.show', $shift->id) }}" class="btn btn-sm btn-light"
                                    title="View">
                                    <iconify-icon icon="mdi:eye"></iconify-icon>
                                </a>

                                <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-sm btn-success"
                                    title="Edit">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                </a>

                                <form method="POST" action="{{ route('shifts.destroy', $shift->id) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Delete {{ $shift->name }}?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No shifts found.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Showing {{ $shifts->firstItem() ?? 0 }} to {{ $shifts->lastItem() ?? 0 }} of {{ $shifts->total() }}
                entries</span>
            <div>{{ $shifts->links() }}</div>
        </div>
    </div>
</div>

<!-- Create Shift Modal -->
<div class="modal fade" id="createShiftModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content radius-16">

            <div class="modal-header">
                <h5 class="modal-title">Create New Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('shifts.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Shift Name</label>
                            <input type="text" name="shift_name" class="form-control" required
                                placeholder="Morning, Night, etc.">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="clock_in_time" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Time</label>
                            <input type="time" name="clock_out_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection