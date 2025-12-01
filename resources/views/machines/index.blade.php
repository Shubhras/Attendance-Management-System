@extends('layout.layout')
@php
$title = 'Machines';
$subTitle = 'Machines';
@endphp

@section('content')

<div class="card h-100 p-0 radius-12">
    <div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>

            <form method="GET" action="{{ route('machines.index') }}" class="d-flex align-items-center gap-2">
                <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px"
                    onchange="this.form.submit()">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>

                <div class="navbar-search">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" value="{{ request('search') }}"
                        placeholder="Search">
                    <button class="btn" type="submit" style="border:none;background:transparent;">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </button>
                </div>

                <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" name="status" disabled>
                    <option>Status</option>
                </select>
            </form>
        </div>

        <button type="button"
            class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#createMachineModal">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Add New Machine
        </button>
    </div>

    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="d-flex align-items-center gap-10">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input radius-4 border input-form-dark" type="checkbox"
                                        id="selectAll">
                                </div>
                                S.L
                            </div>
                        </th>
                        <th scope="col">Create Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Image</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($machines as $index => $machine)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-10">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input radius-4 border border-neutral-400" type="checkbox"
                                        name="checkbox">
                                </div>
                                {{ $machines->firstItem() + $index }}
                            </div>
                        </td>
                        <td>{{ $machine->created_at?->format('d M Y') }}</td>
                        <td>{{ $machine->name }}</td>
                        <td>
                            <p class="max-w-500-px">{{ \Illuminate\Support\Str::limit($machine->description, 80) }}</p>
                        </td>
                        <td>
                            @if($machine->image)
                            <img src="{{ asset($machine->image) }}" alt="image" width="60" class="rounded">
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-10 justify-content-center">
                                <a href="{{ route('machines.edit', $machine->uuid) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="Edit">
                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                </a>

                                <!-- <button type="button"
                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle delete-btn"
                                     data-bs-toggle="modal" data-bs-target="#deletemachineModal"
                                    data-id="{{ $machine->uuid }}"
                                    data-name="{{ $machine->name }}"
                                    title="Delete">
                                    <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                </button> -->
                                <form method="POST" action="{{ route('machines.destroy', $machine->uuid) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Delete {{ $machine->name }}?')">Delete</button>
                                </form>

                                <a href="{{ route('machines.show', $machine->uuid) }}"
                                    class="bg-neutral-200 text-secondary-light fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="View">
                                    <iconify-icon icon="mdi:eye" class="menu-icon"></iconify-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No machines found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Showing {{ $machines->firstItem() ?? 0 }} to {{ $machines->lastItem() ?? 0 }} of
                {{ $machines->total() }} entries</span>
            <div>
                {{ $machines->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal Start -->
<div class="modal fade" id="createMachineModal" tabindex="-1" aria-labelledby="createMachineLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="createMachineLabel">Add New Machine</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                <form id="machineCreateForm" action="{{ route('machines.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control radius-8"
                                placeholder="Enter Name">
                            <div class="invalid-feedback text-danger name-error"></div>
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Image</label>
                            <input type="file" name="image" accept="image/*" class="form-control radius-8">
                            <div class="invalid-feedback text-danger image-error"></div>
                        </div>
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Manager Names</label>
                            <div id="managerContainer">
                                @if(isset($machine) && $machine->manager_names)
                                @foreach($machine->manager_names as $manager)
                                <div class="d-flex gap-2 mb-2">
                                    <input type="text" name="manager_names[]" class="form-control radius-8"
                                        value="{{ $manager }}" placeholder="Enter manager name">
                                    <button type="button" class="btn btn-danger removeManager">Remove</button>
                                </div>
                                @endforeach
                                @else
                                <div class="d-flex gap-2 mb-2">
                                    <input type="text" name="manager_names[]" class="form-control radius-8"
                                        placeholder="Enter manager name">
                                    <button type="button" class="btn btn-danger removeManager">Remove</button>
                                </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addManagerBtn">+ Add
                                Manager</button>
                        </div>

                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Description</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Write some text">{{ old('description') }}</textarea>
                            <div class="invalid-feedback text-danger description-error"></div>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="reset"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                                Cancel
                            </button>
                            <button type="submit" id="machineCreateSubmit"
                                class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Create Modal End -->

<!-- Delete Confirmation Modal -->

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    // Add new manager input field (delegated binding)
    $('body').on('click', '#addManagerBtn', function () {
        console.log('Adding manager field');
        const managerField = `
            <div class="d-flex gap-2 mb-2">
                <input type="text" name="manager_names[]" class="form-control radius-8" placeholder="Enter manager name">
                <button type="button" class="btn btn-danger removeManager">Remove</button>
            </div>
        `;
        $('#managerContainer').append(managerField);
    });

    // Remove manager input field
    $('body').on('click', '.removeManager', function () {
        $(this).closest('.d-flex').remove();
    });

    // (keep your other code here)
});

</script>
@section('scripts')
<!-- jQuery + SweetAlert assumed included in layout; include here if not -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {
    // CSRF setup for AJAX if you use DELETE type requests (optional)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // jQuery border-red validation for create form (client-side)
    $('#machineCreateForm').on('submit', function(e) {
        // remove any previous styling
        $(this).find('input, textarea').css('border', '');
        $('.invalid-feedback').text('');

        let name = $.trim($(this).find('input[name="name"]').val());
        let description = $.trim($(this).find('textarea[name="description"]').val());
        let valid = true;

        if (!name) {
            $(this).find('input[name="name"]').css('border', '1px solid red');
            $('.name-error').text('Name is required');
            valid = false;
        }

        if (!description) {
            $(this).find('textarea[name="description"]').css('border', '1px solid red');
            $('.description-error').text('Description is required');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill required fields highlighted in red.'
            });
        }
    });

    // Attach delete handler (delegated)
    // let deleteUuid = null;
    // $(document).on('click', '.delete-btn', function() {
    //     deleteUuid = $(this).data('id');
    //     $('#deleteName').text($(this).data('name'));
    //     $('#deleteModal').modal('show');
    // });
    document.addEventListener('DOMContentLoaded', function() {
        // alert();
        var deleteModal = document.getElementById('deletemachineModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var uuid = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var form = document.getElementById('deleteForm');

            // ✅ Make sure this line sets the correct action
            form.action = '/machines/' + uuid;

            document.getElementById('deleteName').innerText = name;
        });
    });

    // show success/error from backend via session
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 1800,
        showConfirmButton: false
    });
    @endif
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}"
    });
    @endif
});
</script>
@endsection