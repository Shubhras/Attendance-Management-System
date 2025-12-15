@extends('layout.layout')
@php
$title='Contractors';
$subTitle = 'Contractors';
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
    <div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>

            <form method="GET" action="{{ route('contractors.index') }}" class="d-flex align-items-center gap-2">
                <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px"
                    onchange="this.form.submit()">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>

                <div class="navbar-search">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" value="{{ request('search') }}"
                        placeholder="Search">
                    <button class="btn btn-primary" type="submit">
                       submit
                    </button>
                </div>
                                <!-- Last 3 Months Report Button -->
            <a href="{{ route('contractors.report.last3months') }}"
               class="btn btn-success text-sm btn-sm px-16 py-12 radius-8 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:download-bold" class="icon"></iconify-icon>
                Last 3 Months Report
            </a>    
            </form>
        </div>
        <button type="button"
            class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#createContractorModal">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Add New Contractor
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
                        <th scope="col">Mobile</th>
                        <th scope="col">Company</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contractors as $index => $contractor)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-10">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input radius-4 border border-neutral-400" type="checkbox"
                                        name="checkbox">
                                </div>
                                {{ $contractors->firstItem() + $index }}
                            </div>
                        </td>
                        <td>{{ $contractor->created_at?->format('d M Y') }}</td>
                        <td>{{ $contractor->name }}</td>
                        <td>{{ $contractor->mobile }}</td>
                        <td>{{ $contractor->company_name ?? '-' }}</td>
                        <td class="text-center">
                            <span
                                class="bg-success-focus text-success-600 border border-success-main px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-10 justify-content-center">
                                <a href="{{ route('contractors.edit', $contractor->uuid) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="Edit">
                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                </a>
                            <!-- Download Report Button -->
                                    <a href="{{ route('contractors.report.download', $contractor->id) }}?month={{ now()->month }}&year={{ now()->year }}"
                                    class="bg-info-focus text-info bg-hover-info-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="Download Monthly Report">
                                        <iconify-icon icon="solar:download-minimalistic-bold" class="menu-icon"></iconify-icon>
                                    </a>
                                <form method="POST" action="{{ route('contractors.destroy', $contractor->uuid) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Delete {{ $contractor->name }}?')">Delete</button>
                                </form>

                                <a href="{{ route('contractors.show', $contractor->uuid) }}"
                                    class="bg-neutral-200 text-secondary-light fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="View">
                                    <iconify-icon icon="mdi:eye" class="menu-icon"></iconify-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No contractors found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Showing {{ $contractors->firstItem() ?? 0 }} to {{ $contractors->lastItem() ?? 0 }} of
                {{ $contractors->total() }} entries</span>
            <div>
                {{ $contractors->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal Start (replaces Add New Role modal) -->
<div class="modal fade" id="createContractorModal" tabindex="-1" aria-labelledby="createContractorLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="createContractorLabel">Add New Contractor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form action="{{ route('contractors.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control radius-8"
                                placeholder="Enter Name" required>
                            @error('name') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Mobile</label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control radius-8"
                                placeholder="Enter Mobile" required>
                            @error('mobile') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control radius-8"
                                placeholder="Enter Email">
                            @error('email') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Company</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}"
                                class="form-control radius-8" placeholder="Enter Company Name">
                            @error('company_name') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gov ID</label>
                            <input type="text" name="govid" value="{{ old('govid') }}" class="form-control radius-8"
                                placeholder="Gov ID">
                            @error('govid') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">DOB</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" class="form-control radius-8">
                            @error('dob') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Address</label>
                            <textarea name="address" class="form-control" rows="3"
                                placeholder="Address...">{{ old('address') }}</textarea>
                            @error('address') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gender</label>
                            <select name="gender" class="form-select radius-8">
                                <option value="">Select</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Self Photo</label>
                            <input type="file" name="self_photo" accept="image/*" class="form-control radius-8">
                            @error('self_photo') <div class="text-danger text-sm mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="reset"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                                Cancel
                            </button>
                            <button type="submit"
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
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{--<form method="POST" id="deleteForm" action="{{ route('contractors.destroy', $contractor->uuid) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteName"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-8 radius-8"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-24 py-8 radius-8">Delete</button>
                </div>
            </form>--}}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var uuid = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var form = document.getElementById('deleteForm');

        // ✅ Make sure this line sets the correct action
        form.action = '/contractors/' + uuid;

        document.getElementById('deleteName').innerText = name;
    });
});
</script>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var uuid = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var form = document.getElementById('deleteForm');
            form.action = '/contractors/' + uuid;
            document.getElementById('deleteName').innerText = name;
        });
    });
</script> -->
@endsection