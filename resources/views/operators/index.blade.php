@extends('layout.layout')
@php
    $title='Operators';
    $subTitle = 'Assign Operators to Employees';
@endphp

@section('content')

<div class="card h-100 p-0 radius-12">
    <!-- Header -->
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>

            <form method="GET" action="{{ route('operators.index') }}" class="d-flex align-items-center gap-2">
                <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>

                <div class="navbar-search">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" value="{{ request('search') }}" placeholder="Search">
                    <button class="btn" type="submit" style="border:none;background:transparent;">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </button>
                </div>
            </form>
        </div>

        <!-- Add New Operator Button -->
        <button type="button"
            class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#assignOperatorModal">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Add Operator
        </button>
    </div>

    <!-- Table Body -->
    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="d-flex align-items-center gap-10">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input radius-4 border input-form-dark" type="checkbox" id="selectAll">
                                </div>
                                S.L
                            </div>
                        </th>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Type</th>
                        <th scope="col">Operator Email</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $index => $employee)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-10">
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input class="form-check-input radius-4 border border-neutral-400" type="checkbox" name="checkbox">
                                    </div>
                                    {{ $employees->firstItem() + $index }}
                                </div>
                            </td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->mobile }}</td>
                            <td>{{ ucfirst($employee->employee_type) }}</td>
                            <td>{{ $employee->user?->email ?? '-' }}</td>
                            <td class="text-center">
                                @if($employee->is_operator)
                                    <span class="bg-success-focus text-success-600 border border-success-main px-24 py-4 radius-4 fw-medium text-sm">Assigned</span>
                                @else
                                    <span class="bg-warning-focus text-warning-600 border border-warning-main px-24 py-4 radius-4 fw-medium text-sm">Not Assigned</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    @if(!$employee->is_operator)
                                        <button type="button"
                                            class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                            data-bs-toggle="modal" data-bs-target="#assignOperatorModal"
                                            data-employee-id="{{ $employee->id }}"
                                            data-employee-name="{{ $employee->name }}">
                                            <iconify-icon icon="mdi:account-plus" class="menu-icon"></iconify-icon>
                                        </button>
                                    @else
                                        <form action="{{ route('operators.destroy', $employee->uuid) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-danger-focus text-danger-600 bg-hover-danger-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                onclick="return confirm('Remove this operator?')">
                                                <iconify-icon icon="mdi:account-remove" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} entries</span>
            <div>{{ $employees->links() }}</div>
        </div>
    </div>
</div>

<!-- Assign Operator Modal -->
<div class="modal fade" id="assignOperatorModal" tabindex="-1" aria-labelledby="assignOperatorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="assignOperatorLabel">Assign Operator</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                <form action="{{ route('operators.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Select Employee</label>
                            <select name="employee_id" id="employeeSelect" class="form-select radius-8" required>
                                <option value="">Select Employee</option>
                                @foreach($allEmployees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email</label>
                            <input type="email" name="email" class="form-control radius-8" placeholder="Enter Email" required>
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Password</label>
                            <input type="password" name="password" class="form-control radius-8" placeholder="Enter Password" required>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8">Assign</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var assignModal = document.getElementById('assignOperatorModal');
        assignModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var employeeId = button.getAttribute('data-employee-id');
            var employeeName = button.getAttribute('data-employee-name');

            // Pre-select employee if triggered from table button
            if (employeeId) {
                let select = document.getElementById('employeeSelect');
                select.value = employeeId;
            }
        });
    });
</script>
@endsection
