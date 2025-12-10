@extends('layout.layout')
@php
$title = 'Edit Employee';
$subTitle = 'Update Employee Details';
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
<div class="card radius-12 p-24">
    <h5 class="mb-3">Edit Employee</h5>
    <form action="{{ route('employees-get.update', $employee->uuid) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ $employee->name }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Mobile</label>
                <input type="text" name="mobile" value="{{ $employee->mobile }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Joining Date</label>
                <input type="date" name="joining_date" value="{{ $employee->joining_date }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Work Title / Job Title</label>
                <input type="text" name="employee_work_title" value="{{ $employee->employee_work_title }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Gov ID</label>
                <input type="text" name="govid" value="{{ $employee->govid }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">DOB</label>
                <input type="date" name="dob" value="{{ $employee->dob }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Aadhaar Card</label><br>
                @if($employee->aadhar_card)
                @php
                $extension = pathinfo($employee->aadhar_card, PATHINFO_EXTENSION);
                @endphp

                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                <img src="{{ asset($employee->aadhar_card) }}" alt="Aadhaar" width="100" class="mb-2 rounded border">
                @elseif(strtolower($extension) === 'pdf')
                <a href="{{ asset($employee->aadhar_card) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    View Aadhaar PDF
                </a>
                @else
                <p class="text-muted small">File uploaded: {{ basename($employee->aadhar_card) }}</p>
                @endif
                @endif
                <input type="file" name="aadhar_card" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Shift</label>
                <select name="shift_id" class="form-select">
                    <option value="">Select shift</option>
                    @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" {{ $employee->shift_id == $shift->id ? 'selected' : '' }}>
                        {{ $shift->shift_name }} ({{ $shift->clock_in_time }} - {{ $shift->clock_out_time }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select</option>
                    <option value="male" {{ $employee->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $employee->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $employee->gender == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Photo</label><br>
                @if($employee->photo)
                <img src="{{ asset($employee->photo) }}" alt="photo" width="80" class="mb-2 rounded">
                @endif
                <input type="file" name="photo" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fingerprint</label>
                @if($employee->fingerprint)
                <p class="text-muted small">Current file: {{ basename($employee->fingerprint) }}</p>
                @endif
                <input type="file" name="fingerprint" class="form-control">
            </div>
            <!-- <div class="col-md-6">
                <label class="form-label">Employee Type</label>
                <select name="employee_type" class="form-select">
                    <option value="company" {{ $employee->employee_type == 'company' ? 'selected' : '' }}>Company
                    </option>
                    <option value="contractor" {{ $employee->employee_type == 'contractor' ? 'selected' : '' }}>
                        Contractor</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Contractor</label>
                <select name="contractor_id" class="form-select">
                    <option value="">Select Contractor</option>
                    @foreach($contractors as $contractor)
                    <option value="{{ $contractor->id }}"
                        {{ $employee->contractor_id == $contractor->id ? 'selected' : '' }}>
                        {{ $contractor->name }}
                    </option>
                    @endforeach
                </select>
            </div> -->
            <div class="col-md-6">
    <label class="form-label">Employee Type</label>
    <select name="employee_type" id="employee_type" class="form-select" required>
        <option value="">Select employee type</option>
        <option value="company" {{ $employee->employee_type == 'company' ? 'selected' : '' }}>Company</option>
        <option value="contractor" {{ $employee->employee_type == 'contractor' ? 'selected' : '' }}>Contractor</option>
    </select>
</div>

<div class="col-md-6" id="contractor_box" style="{{ $employee->employee_type == 'contractor' ? '' : 'display:none;' }}">
    <label class="form-label">Contractor</label>
    <select name="contractor_id" class="form-select">
        <option value="">Select Contractor</option>
        @foreach($contractors as $contractor)
        <option value="{{ $contractor->id }}" 
            {{ $employee->contractor_id == $contractor->id ? 'selected' : '' }}>
            {{ $contractor->name }}
        </option>
        @endforeach
    </select>
</div>

            <div class="col-md-6">
                <label class="form-label">Machine</label>
                <select name="machine_id" class="form-select">
                    <option value="">Select machine</option>
                    @foreach($machines as $machine)
                    <option value="{{ $machine->id }}"
                        {{ old('machine_id', $employee->machine_id) == $machine->id ? 'selected' : '' }}>
                        {{ $machine->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <!-- <div class="col-md-6">
                <label class="form-label">Salary (₹)</label>
                <input type="number" step="0.01" name="salary_monthly" value="{{ $employee->salary_monthly }}"
                    class="form-control">
            </div> -->
            <div class="col-md-6">
                <label class="form-label">Salary Type</label>
                <select name="salary_type" id="salaryType" class="form-select" required>
                    <option value="monthly" {{ $employee->salary_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="daily" {{ $employee->salary_type == 'daily' ? 'selected' : '' }}>Daily</option>
                </select>
            </div>

            <div class="col-md-6 salary-monthly-field"
                style="{{ $employee->salary_type == 'monthly' ? '' : 'display:none;' }}">
                <label class="form-label">Monthly Salary (₹)</label>
                <input type="number" step="0.01" name="salary_monthly"
                    value="{{ old('salary_monthly', $employee->salary_monthly) }}" class="form-control"
                    placeholder="Enter monthly salary">
            </div>

            <div class="col-md-6 salary-daily-field"
                style="{{ $employee->salary_type == 'daily' ? '' : 'display:none;' }}">
                <label class="form-label">Daily Salary (₹)</label>
                <input type="number" step="0.01" name="salary_daily"
                    value="{{ old('salary_daily', $employee->salary_daily) }}" class="form-control"
                    placeholder="Enter daily salary">
            </div>

            <div class="col-md-6">
                <label class="form-label">Company Department</label>
                <input type="text" name="company_department" value="{{ $employee->company_department }}"
                    class="form-control">
            </div>
        </div>
        <div class="text-end mt-3">
            <a href="{{ route('employees-get.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const salaryType = document.getElementById('salaryType');
    const monthlyField = document.querySelector('.salary-monthly-field');
    const dailyField = document.querySelector('.salary-daily-field');

    salaryType.addEventListener('change', function() {
        if (this.value === 'monthly') {
            monthlyField.style.display = '';
            dailyField.style.display = 'none';
        } else {
            monthlyField.style.display = 'none';
            dailyField.style.display = '';
        }
    });
});
</script> -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Salary toggle (already working)
    const salaryType = document.getElementById('salaryType');
    const monthlyField = document.querySelector('.salary-monthly-field');
    const dailyField = document.querySelector('.salary-daily-field');

    salaryType.addEventListener('change', function () {
        monthlyField.style.display = (this.value === 'monthly') ? '' : 'none';
        dailyField.style.display = (this.value === 'daily') ? '' : 'none';
    });

    // Employee Type toggle (new)
    const empType = document.getElementById("employee_type");
    const contractorBox = document.getElementById("contractor_box");

    function toggleContractor() {
        contractorBox.style.display = empType.value === "contractor" ? "block" : "none";
    }

    empType.addEventListener("change", toggleContractor);
    toggleContractor(); // Run on page load
});
</script>

