@extends('layout.layout')
@php
    $title='Contractor Details';
    $subTitle = 'Contractor Details';
@endphp

@section('content')
<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <h4>{{ $contractor->name }}</h4>
        <a href="{{ route('contractors.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card-body p-24">
        <div class="row">
            <div class="col-md-4">
                @if($contractor->self_photo)
                    <img src="{{ asset('storage/'.$contractor->self_photo) }}" class="w-100 radius-8" alt="self photo">
                @else
                    <div class="p-24 bg-neutral-100 radius-8 text-center">No Photo</div>
                @endif
            </div>
            <div class="col-md-8">
                <p><strong>Mobile:</strong> {{ $contractor->mobile }}</p>
                <p><strong>Email:</strong> {{ $contractor->email ?: '-' }}</p>
                <p><strong>Company:</strong> {{ $contractor->company_name ?: '-' }}</p>
                <p><strong>Gov ID:</strong> {{ $contractor->govid ?: '-' }}</p>
                <p><strong>DOB:</strong> {{ $contractor->dob?->format('d M Y') ?: '-' }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($contractor->gender) ?: '-' }}</p>
                <p><strong>Address:</strong> {{ $contractor->address ?: '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
