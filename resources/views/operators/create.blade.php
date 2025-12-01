@extends('layout.layout')

@section('content')
<div class="card radius-12 p-24">
    <h5 class="mb-3">Assign Operator to Employee</h5>

    <form action="{{ route('operators.store') }}" method="POST">
        @csrf
        <input type="hidden" name="employee_uuid" value="{{ $employee->uuid }}">

        <div class="mb-3">
            <label>Employee Name</label>
            <input type="text" class="form-control" value="{{ $employee->name }}" readonly>
        </div>

        <div class="mb-3">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control border-danger" required>
        </div>

        <div class="mb-3">
            <label>Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control border-danger" required>
        </div>

        <button type="submit" class="btn btn-primary">Assign Operator</button>
        <a href="{{ route('operators.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
