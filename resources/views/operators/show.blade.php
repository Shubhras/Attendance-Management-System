@extends('layout.layout')

@section('content')
<div class="card radius-12 p-24">
    <h5 class="mb-3">Operator Details</h5>

    <p><strong>Name:</strong> {{ $employee->name }}</p>
    <p><strong>Mobile:</strong> {{ $employee->mobile }}</p>
    <p><strong>Email:</strong> {{ $employee->user->email ?? '—' }}</p>
    <p><strong>Status:</strong> 
    @if($employee->is_operator)
        <span class="badge bg-success">Active Operator</span>
    @elseif($employee->is_hr)
        <span class="badge bg-info">Active HR</span>
    @else
        <span class="badge bg-secondary">Not Assigned</span>
    @endif
</p>

    <a href="{{ route('operators.index') }}" class="btn btn-secondary mt-3" style="width:10%">Back</a>
</div>
@endsection
