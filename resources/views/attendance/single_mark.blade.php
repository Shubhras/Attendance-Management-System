@extends('layout.layout')
@php
    $title = 'Mark Attendance';
    $subTitle = 'Add Attendance';
@endphp

@section('content')
<div class="card radius-12 p-24">
    <h5 class="mb-3">Mark Attendance</h5>

    <form action="{{ route('attendance.storeSingle') }}" method="POST">
        @csrf

        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
        <input type="date"
       name="date"
       class="form-control"
       value="{{ $date ?? now()->format('Y-m-d') }}">
        <!-- <input type="hidden" name="date" value="{{ $date }}"> -->
        <!-- <input type="hidden" name="machine_id" value="{{ $machineId }}"> -->
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Employee</label>
                <input type="text" class="form-control" value="{{ $employee->name }}" readonly>
            </div>
<div class="col-md-6">
    <label class="form-label">Date</label>
    <input type="date"
           name="date"
           class="form-control"
           value="{{ $date ?? now()->format('Y-m-d') }}">
</div>
            <!-- <div class="col-md-6">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" value="{{ $date }}">
            </div> -->
            <div class="col-md-4">
                <label class="form-label">Machine</label>
                <select name="machine_id" class="form-select">
                    <option value="">Select Machine</option>

                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}"
                            {{ $machineId == $machine->id ? 'selected' : '' }}>
                            {{ $machine->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1">Present</option>
                    <option value="2">Half Day</option>
                    <option value="0">Leave / Absent</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Clock In</label>
                <input type="time" name="clock_in" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Clock Out</label>
                <input type="time" name="clock_out" class="form-control">
            </div>

        </div>

        <div class="text-end mt-3">
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>

    </form>
</div>
@endsection
