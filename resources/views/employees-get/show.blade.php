@extends('layout.layout')
@php
$title = 'Employee Details';
$subTitle = 'View Employee';
@endphp

@section('content')
<div class="card radius-12 p-24">
    <h5 class="mb-3">Employee Details</h5>
    <div class="row g-3">
        <div class="col-md-4"><strong>Name:</strong> {{ $employee->name }}</div>
        <div class="col-md-4"><strong>Mobile:</strong> {{ $employee->mobile }}</div>
        <div class="col-md-4"><strong>Gov ID:</strong> {{ $employee->govid ?? '-' }}</div>
        <div class="col-md-4"><strong>DOB:</strong> {{ $employee->dob ?? '-' }}</div>
        <div class="col-md-4"><strong>Joining Date:</strong> {{ $employee->joining_date ?? '-' }}</div>
        <div class="col-md-4"><strong>Work Title:</strong> {{ $employee->employee_work_title ?? '-' }}</div>
        <div class="col-md-4"><strong>Gender:</strong> {{ ucfirst($employee->gender) ?? '-' }}</div>
        <div class="col-md-4"><strong>Employee Type:</strong> {{ ucfirst($employee->employee_type) }}</div>
        <div class="col-md-4"><strong>Contractor:</strong> {{ $employee->contractor?->name ?? '-' }}</div>
        <div class="col-md-4"><strong>Machine:</strong> {{ $employee->machine ?? '-' }}</div>
        <!-- <div class="col-md-4"><strong>Salary (₹):</strong> {{ $employee->salary_monthly ?? '-' }}</div> -->
        {{-- ✅ Show Salary Type and Value --}}
        <div class="col-md-4">
            <strong>Salary Type:</strong> {{ ucfirst($employee->salary_type) }}
        </div>
        <div class="col-md-4">
            @if($employee->salary_type === 'monthly')
            <strong>Monthly Salary (₹):</strong> {{ number_format($employee->salary_monthly ?? 0, 2) }}
            @else
            <strong>Daily Salary (₹):</strong> {{ number_format($employee->salary_daily ?? 0, 2) }}
            @endif
        </div>
        <div class="col-md-4"><strong>Department:</strong> {{ $employee->company_department ?? '-' }}</div>
        <div class="col-md-4"><strong>Photo:</strong><br>
            @if($employee->photo)
            <img src="{{ asset($employee->photo) }}" width="100" class="rounded mt-2">
            @else
            <span class="text-muted">No photo</span>
            @endif
        </div>
        <div class="col-md-4"><strong>Fingerprint File:</strong><br>
            @if($employee->fingerprint)
            <img src="{{ asset($employee->fingerprint) }}" width="100" class="rounded mt-2">
            @else
            <span class="text-muted">No photo</span>
            @endif
        </div>
        <div class="col-md-4"><strong>Adhar File:</strong><br>
            @if($employee->aadhar_card)
            <img src="{{ asset($employee->aadhar_card) }}" width="100" class="rounded mt-2">
            @else
            <span class="text-muted">No photo</span>
            @endif
        </div>
    </div>
    <div class="mt-3 text-end">
        <a href="{{ route('employees-get.index') }}" class="btn btn-secondary">Back</a>
        <a href="{{ route('employees-get.edit', $employee->uuid) }}" class="btn btn-primary">Edit</a>
    </div>
</div>
@endsection