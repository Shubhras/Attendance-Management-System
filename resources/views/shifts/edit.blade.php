@extends('layout.layout')
@php
    $title = 'Edit Shift';
    $subTitle = 'Update Shift';
@endphp

@section('content')
<div class="card radius-12 p-24">

    <h5 class="mb-3">Edit Shift</h5>

    <form action="{{ route('shifts.update', $shift->uuid) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <div class="col-md-12">
                <label class="form-label">Shift Name</label>
                <input type="text" name="shift_name" value="{{ $shift->shift_name }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Start Time</label>
                <input type="time" name="clock_in_time" class="form-control" value="{{ $shift->clock_in_time }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">End Time</label>
                <input type="time" name="clock_out_time" class="form-control" value="{{ $shift->clock_out_time }}" required>
            </div>

        </div>

        <div class="text-end mt-3">
            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>

    </form>

</div>
@endsection
