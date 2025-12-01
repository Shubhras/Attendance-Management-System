@extends('layout.layout')

@section('content')
<div class="card radius-12 p-24">
    <h5 class="mb-3">Edit Operator</h5>

    <form action="{{ route('operators.update', $employee->uuid) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $employee->user->email }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>New Password (optional)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Operator</button>
        <a href="{{ route('operators.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
