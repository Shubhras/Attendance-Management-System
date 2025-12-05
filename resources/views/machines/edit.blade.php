@extends('layout.layout')

@php
    $title = 'Edit Machine';
    $subTitle = 'Machines';
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
  <div class="card-body p-24">
    <form action="{{ route('machines.update', $machine->uuid) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control radius-8" value="{{ old('name', $machine->name) }}">
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $machine->description) }}</textarea>
        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      {{-- ✅ Manager Names Section --}}
      <div class="mb-3">
        <label class="form-label">Manager Names</label>
        <div id="managerContainer">
          @php
              $managers = is_array($machine->manager_names)
                  ? $machine->manager_names
                  : (json_decode($machine->manager_names, true) ?? []);
          @endphp

          @forelse($managers as $manager)
            <div class="d-flex gap-2 mb-2">
              <input type="text" name="manager_names[]" class="form-control radius-8" value="{{ $manager }}" placeholder="Enter manager name">
              <button type="button" class="btn btn-danger removeManager">Remove</button>
            </div>
          @empty
            <div class="d-flex gap-2 mb-2">
              <input type="text" name="manager_names[]" class="form-control radius-8" placeholder="Enter manager name">
              <button type="button" class="btn btn-danger removeManager">Remove</button>
            </div>
          @endforelse
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addManagerBtn">+ Add Manager</button>
      </div>

      <div class="mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
        @if($machine->image)
          <div class="mt-2">
            <img src="{{ asset($machine->image) }}" width="100" class="rounded">
          </div>
        @endif
      </div>

      <div class="d-flex gap-2 mt-3">
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('machines.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Add new manager input field
    $('body').on('click', '#addManagerBtn', function () {
        const field = `
            <div class="d-flex gap-2 mb-2">
                <input type="text" name="manager_names[]" class="form-control radius-8" placeholder="Enter manager name">
                <button type="button" class="btn btn-danger removeManager">Remove</button>
            </div>
        `;
        $('#managerContainer').append(field);
    });

    // Remove manager input field
    $('body').on('click', '.removeManager', function () {
        $(this).closest('.d-flex').remove();
    });
});
</script>

