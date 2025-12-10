@extends('layout.layout')
@php
    $title='Edit Contractor';
    $subTitle = 'Edit Contractor';
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
<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
        <h4>Edit Contractor: {{ $contractor->name }}</h4>
        <a href="{{ route('contractors.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card-body p-24">
        <form action="{{ route('contractors.update', $contractor->uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- similar fields as create (name, mobile, email, etc) -->
                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Name</label>
                    <input type="text" name="name" value="{{ old('name', $contractor->name) }}" class="form-control radius-8" required>
                    @error('name') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Mobile</label>
                    <input type="text" name="mobile" value="{{ old('mobile', $contractor->mobile) }}" class="form-control radius-8" required>
                    @error('mobile') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email</label>
                    <input type="email" name="email" value="{{ old('email', $contractor->email) }}" class="form-control radius-8">
                    @error('email') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Company</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $contractor->company_name) }}" class="form-control radius-8">
                    @error('company_name') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gov ID</label>
                    <input type="text" name="govid" value="{{ old('govid', $contractor->govid) }}" class="form-control radius-8">
                    @error('govid') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">DOB</label>
                    <input type="date" name="dob" value="{{ old('dob', optional($contractor->dob)->format('Y-m-d')) }}" class="form-control radius-8">
                    @error('dob') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $contractor->address) }}</textarea>
                    @error('address') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gender</label>
                    <select name="gender" class="form-select radius-8">
                        <option value="">Select</option>
                        <option value="male" {{ old('gender', $contractor->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $contractor->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $contractor->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Self Photo</label>
                    <input type="file" name="self_photo" accept="image/*" class="form-control radius-8">
                    @if($contractor->self_photo)
                        <div class="mt-10">
                            <img src="{{ asset($contractor->self_photo) }}" width="100" class="rounded">
                            <!-- <img src="{{ asset('$contractor->self_photo') }}" alt="photo" style="max-height:120px;border-radius:8px;"> -->
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                    <a href="{{ route('contractors.index') }}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
