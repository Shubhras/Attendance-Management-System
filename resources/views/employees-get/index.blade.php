@extends('layout.layout')
@php
$title = 'Employees';
$subTitle = 'Manage Employees';
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
    <div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('employees-get.index') }}" class="d-flex align-items-center gap-2">
                <!-- Per Page -->
                <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px"
                    onchange="this.form.submit()">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>

                <!-- Search -->
                <div class="navbar-search">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" value="{{ request('search') }}"
                        placeholder="Search name or mobile">
                    <button class="btn" type="submit" style="border:none;background:transparent;">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </button>
                </div>
            </form>
        </div>

        <button type="button"
            class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Add Employee
        </button>
    </div>

    <!-- Table Section -->
    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Created</th>
                        <th>Name</th>
                        <th>Shift</th>
                        <th>Mobile</th>
                        <th>Work Title</th>
                        <th>Joining Date</th>
                        <th>Gender</th>
                        <th>Employee Type</th>
                        <th>Contractor</th>
                        <th>Machine</th>
                        <th>Salary (₹)</th>
                        <th>Department</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $index => $employee)
                    <tr>
                        <td>{{ $employees->firstItem() + $index }}</td>
                        <td>{{ $employee->created_at?->format('d M Y') }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->shift?->shift_name ?? '-' }}</td>
                        <td>{{ $employee->mobile }}</td>
                        <td>{{ $employee->employee_work_title ?? '-' }}</td>
                        <td>{{ $employee->joining_date ?? '-' }}</td>
                        <td>{{ ucfirst($employee->gender) }}</td>
                        <td>{{ ucfirst($employee->employee_type) }}</td>
                        <td>{{ $employee->contractor?->name ?? '-' }}</td>
                        <td>                        @php
                            $machineName = $machine->firstWhere('id', $employee->machine_id)?->name ?? 'Unassigned';
                        @endphp
                        {{ $machineName }}
                        </td>
                        <!-- <td>{{ $employee->machine->name ?? '-' }}</td> -->
                        <!-- <td>{{ $employee->machine ?? '-' }}</td> -->
                        <td>
                            @if($employee->salary_type === 'daily')
                            ₹ {{ number_format($employee->salary_daily, 2) }} <small class="text-muted">/ day</small>
                            @elseif($employee->salary_type === 'monthly')
                            ₹ {{ number_format($employee->salary_monthly, 2) }} <small class="text-muted">/
                                month</small>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <!-- <td>{{ $employee->salary_monthly ? number_format($employee->salary_monthly, 2) : '-' }}</td> -->
                        <td>{{ $employee->company_department ?? '-' }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('employees-get.show', $employee->uuid) }}"
                                    class="btn btn-sm btn-light" title="View">
                                    <iconify-icon icon="mdi:eye"></iconify-icon>
                                </a>
                                <a href="{{ route('employees-get.edit', $employee->uuid) }}"
                                    class="btn btn-sm btn-success" title="Edit">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                </a>
                                <form method="POST" action="{{ route('employees-get.destroy', $employee->uuid) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Delete {{ $employee->name }}?')">Delete</button>
                                </form>
                                <!-- <button type="button" class="btn btn-sm btn-danger delete-btn"
                                    data-id="{{ $employee->uuid }}" data-name="{{ $employee->name }}"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                </button> -->
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of
                {{ $employees->total() }} entries</span>
            <div>
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employees-get.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Enter name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Shift</label>
                            <select name="shift_id" class="form-select">
                                <option value="">Select Shift</option>
                                @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->shift_name }} ({{ $shift->clock_in_time }} -
                                    {{ $shift->clock_out_time }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" class="form-control" required
                                placeholder="Enter mobile number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Joining Date</label>
                            <input type="date" name="joining_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Work Title / Job Title</label>
                            <input type="text" name="employee_work_title" class="form-control" placeholder="Ex: Supervisor, Welder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gov ID</label>
                            <input type="text" name="govid" class="form-control" placeholder="Enter government ID">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Aadhaar Card</label>
                            <input type="file" name="aadhar_card" class="form-control" accept="image/*,application/pdf">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">DOB</label>
                            <input type="date" name="dob" class="form-control" placeholder="Select date of birth">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                        <input type="hidden" name="fingerprint" value="false">
                        <!-- <div class="col-md-6">
                            <label class="form-label">Fingerprint</label>
                            <input type="file" name="fingerprint" class="form-control">
                        </div> -->
                        <!-- Employee Type & Contractor -->
                        <div class="col-md-6">
                            <label class="form-label">Employee Type</label>
                            <select name="employee_type" id="employee_type" class="form-select" required>
                                <option value="">Select employee type</option>
                                <option value="company">Company</option>
                                <option value="contractor">Contractor</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="contractor_box" style="display:none;">
                            <label class="form-label">Contractor</label>
                            <select name="contractor_id" class="form-select">
                                <option value="">Select contractor</option>
                                @foreach($contractors as $contractor)
                                    <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- <div class="col-md-6">
                            <label class="form-label">Employee Type</label>
                            <select name="employee_type" class="form-select" required>
                                <option value="">Select employee type</option>
                                <option value="company">Company</option>
                                <option value="contractor">Contractor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contractor</label>
                            <select name="contractor_id" class="form-select">
                                <option value="">Select contractor</option>
                                @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <div class="col-md-6">
                            <!-- <label class="form-label">Machine</label>
                            <input type="text" name="machine" class="form-control" placeholder="Enter assigned machine"> -->
                            <label class="form-label">Assign Machine</label>
                            <select name="machine_id" class="form-select">
                                <option value="">Select machine</option>
                                @foreach($machine as $mach)
                                <option value="{{ $mach->id }}">{{ $mach->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- <div class="col-md-6">
                            <label class="form-label">Monthly Salary (₹)</label>
                            <input type="number" step="0.01" name="salary_monthly" class="form-control"
                                placeholder="Enter monthly salary">
                        </div> -->
                        <div class="col-md-6">
                            <label class="form-label">Salary Type</label>
                            <select name="salary_type" id="salaryType" class="form-select">
                                <option value="monthly"
                                    {{ old('salary_type', $employee->salary_type ?? 'monthly') == 'monthly' ? 'selected' : '' }}>
                                    Monthly</option>
                                <option value="daily"
                                    {{ old('salary_type', $employee->salary_type ?? '') == 'daily' ? 'selected' : '' }}>
                                    Daily</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="monthlySalaryDiv">
                            <label class="form-label">Monthly Salary (₹)</label>
                            <input type="number" step="0.01" name="salary_monthly"
                                value="{{ old('salary_monthly', $employee->salary_monthly ?? '') }}"
                                class="form-control" placeholder="Enter monthly salary">
                        </div>

                        <div class="col-md-6 d-none" id="dailySalaryDiv">
                            <label class="form-label">Daily Salary (₹)</label>
                            <input type="number" step="0.01" name="salary_daily"
                                value="{{ old('salary_daily', $employee->salary_daily ?? '') }}" class="form-control"
                                placeholder="Enter daily salary">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company Department</label>
                            <input type="text" name="company_department" class="form-control"
                                placeholder="Enter department">
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const salaryType = document.getElementById('salaryType');
    const monthlyDiv = document.getElementById('monthlySalaryDiv');
    const dailyDiv = document.getElementById('dailySalaryDiv');

    function toggleSalaryFields() {
        if (salaryType.value === 'daily') {
            dailyDiv.classList.remove('d-none');
            monthlyDiv.classList.add('d-none');
        } else {
            monthlyDiv.classList.remove('d-none');
            dailyDiv.classList.add('d-none');
        }
    }

    salaryType.addEventListener('change', toggleSalaryFields);
    toggleSalaryFields(); // initial load
});
</script>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const uuid = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const form = document.getElementById('deleteForm');
        form.action = '/employees/' + uuid;
        document.getElementById('deleteName').innerText = name;
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const employeeType = document.getElementById("employee_type");
    const contractorDiv = document.getElementById("contractor_box");

    function toggleContractor() {
        if (employeeType.value === "contractor") {
            contractorDiv.style.display = "block";
        } else {
            contractorDiv.style.display = "none";
        }
    }

    employeeType.addEventListener("change", toggleContractor);
    toggleContractor(); // initial load
});
</script>

@endsection