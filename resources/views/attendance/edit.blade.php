@extends('layout.layout')
@php
    $title = 'Edit Attendance';
    $subTitle = 'Update Attendance';
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
    <h5 class="mb-3">Edit Attendance</h5>

    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
        @csrf
        <input type="hidden" name="machine_id" value="{{ $attendance->machine_id ?? $attendance->employee->machine_id }}">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Employee</label>
                <input type="text" class="form-control" value="{{ $attendance->employee->name }}" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" value="{{ $attendance->date->format('Y-m-d') }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" {{ $attendance->status == 1 ? 'selected' : '' }}>Present</option>
                    <!-- <option value="half_day" {{ $attendance->status == 'half_day' ? 'selected' : '' }}>Half Day</option> -->
                    <option value="0" {{ $attendance->status == 0 ? 'selected' : '' }}>Leave</option>
                    <option value="2" {{ $attendance->status == 2 ? 'selected' : '' }}>Half day</option>
                    <!-- <option value="pending" {{ $attendance->status == 'pending' ? 'selected' : '' }}>Pending</option> -->
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Clock In</label>
                <input type="time" name="clock_in" class="form-control" value="{{ $attendance->clock_in }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Clock Out</label>
                <input type="time" name="clock_out" class="form-control" value="{{ $attendance->clock_out }}">
            </div>

        </div>

        <div class="text-end mt-3">
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
