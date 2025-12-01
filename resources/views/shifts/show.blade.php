@extends('layout.layout')
@php
    $title = 'Shift Details';
    $subTitle = 'View Shift';
@endphp

@section('content')
<div class="card radius-12 p-24">

    <h5 class="mb-3">Shift Details</h5>

    <div class="row g-3">
        <div class="col-md-4"><strong>Name:</strong> {{ $shift->shift_name }}</div>
        <div class="col-md-4"><strong>Start Time:</strong> {{ $shift->clock_in_time }}</div>
        <div class="col-md-4"><strong>End Time:</strong> {{ $shift->clock_out_time }}</div>
        <div class="col-md-4"><strong>Total Hours:</strong>00</div>
    </div>

    <div class="mt-3 text-end">
        <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Back</a>
        <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-primary">Edit</a>
    </div>

</div>
@endsection
