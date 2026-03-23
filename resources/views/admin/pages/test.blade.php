{{-- resources/views/admin/pages/test.blade.php --}}
@extends("admin.layout.admin-master")

@section("title", "Test Details | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">

            <!-- Breadcrumb -->
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Test Details</li>
                </ol>
            </div>

            <!-- Header: Search + Add Button -->
            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="testSearch" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" id="searchBtn" class="input-group-text"><i
                                class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <div class="ms-auto">
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addTestModal">
                        + Add Test
                    </button>
                    <a href="javascript:void(0);" class="btn btn-danger btn-rounded deleteSelected">Delete Selected</a>
                </div>

            </div>

            <!-- Success Toast -->
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const Toast = Swal.mixin({
                            icon: 'success',
                            title: 'Success!',
                            text: '{{ session("success") }}',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK',
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        Toast.fire({
                            icon: 'success',
                            title: '{{ session('success') }}'
                        });
                    });
                </script>
            @endif

            <!-- Table -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="testTable">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox text-end align-self-center">
                                            <div class="form-check custom-checkbox ">
                                                <input type="checkbox" class="form-check-input" id="checkAll" required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </div>
                                    </th>
                                    <th>Icon</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    {{-- <th>Created At</th> --}}
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="testTableBody">
                                @forelse($tests as $test)
                                    <tr>
                                        <td class="d-flex">
                                            {{ $loop->iteration }}
                                            <div class="checkbox text-end align-self-center ms-2">

                                                <div class="form-check custom-checkbox ">
                                                    <input type="checkbox" class="form-check-input checkItem"
                                                        value="{{ $test->id }}" required="">
                                                    <label class="form-check-label" for="checkbox"></label>
                                                </div>
                                            </div>


                                        </td>
                                        <td>

                                            @if($test->icon)
                                                <img src="{{ asset('storage/' . $test->icon) }}" alt="{{ $test->title }}" width="50"
                                                    class="rounded">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                    style="width:50px;height:50px;">
                                                    <i class="fas fa-vial text-muted fs-4"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="fw-600">{{ $test->title }}</td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $test->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($test->status) }}
                                            </span>
                                        </td>
                                        {{-- <td>
                                            <small>{{ $test->created_at->format('d M, Y') }}</small>
                                        </td> --}}
                                        <td class="text-center">
                                            {{-- <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#viewModal{{ $test->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button> --}}
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $test->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.tests.destroy', $test) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger light delete-btn"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-vial fa-3x mb-3"></i><br>
                                            No tests found. Click "+ Add Test" to create one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addTestModal">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('admin.tests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Test</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <label>Test Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Complete Blood Count"
                                    required>
                            </div>
                            <div class="col-lg-4">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label>Icon (Optional)</label>
                                <input type="file" name="icon" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended: 100x100px PNG/JPG</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View & Edit Modals -->
    @foreach($tests as $test)
        <!-- View Modal -->
        <div class="modal fade" id="viewModal{{ $test->id }}">
            <div class="modal-dialog modal-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $test->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped mb-0">
                            <tr>
                                <th width="150">Title</th>
                                <td colspan="3">{{ $test->title }}</td>
                            </tr>
                            <tr>
                                <th>Icon</th>
                                <td>
                                    @if($test->icon)
                                        <img src="{{ asset('storage/' . $test->icon) }}" width="100" class="rounded">
                                    @else
                                        <span class="text-muted">No icon uploaded</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-{{ $test->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($test->status) }}
                                    </span>
                                </td>

                                <th>Created</th>
                                <td>{{ $test->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $test->id }}">
            <div class="modal-dialog modal-centered">
                <form action="{{ route('admin.tests.update', $test) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Test</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-lg-8">
                                    <label>Test Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ $test->title }}" class="form-control" required>
                                </div>
                                <div class="col-lg-4">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="active" {{ $test->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $test->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label>Change Icon (Leave blank to keep current)</label>
                                    <input type="file" name="icon" class="form-control" accept="image/*">
                                    @if($test->icon)
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . $test->icon) }}" width="80" class="rounded">
                                            <small class="text-muted d-block">Current icon</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(function () {
            // Search functionality
            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $("#testTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // SweetAlert2 Delete Confirmation (Same as FAQs)
            $('.delete-btn').click(function (e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Delete Test?',
                    text: "This Test will be permanently deleted !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        // SELECT ALL
        $("#checkAll").on("change", function () {
            $(".checkItem").prop("checked", $(this).prop("checked"));
        });

        // DELETE SELECTED
        $('.deleteSelected').click(function () {

            let selected = [];

            $(".checkItem:checked").each(function () {
                selected.push($(this).val());
            });

            console.log("Selected IDs:", selected); // debug

            if (selected.length === 0) {
                Swal.fire("Oops!", "Please select at least one Test.", "warning");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Selected test will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete selected!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "/tests/delete-selected",

                        type: "POST",
                        data: {
                            ids: selected,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            Swal.fire("Deleted!", "Selected test removed.", "success");

                            selected.forEach(id => {
                                $(`input[value='${id}']`).closest("tr").fadeOut(500, function () {
                                    $(this).remove();
                                });
                            });
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                            Swal.fire("Error!", "Something went wrong. Check console.", "error");
                        }
                    });

                }
            });

        });


        // search functionality

        const searchInput = document.getElementById('testSearch');
        const tableBody = document.getElementById('testTableBody');

        searchInput.addEventListener('keyup', function () {

            let keyword = this.value.trim();

            fetch(`/tests/search?keyword=${keyword}`)
                .then(res => res.json())
                .then(res => {

                    let html = '';

                    if (res.data.length > 0) {

                        res.data.forEach(item => {
                            html += `
                            <tr>
                                <td>
                                    <input type="checkbox" class="checkItem" value="${item.id}">
                                </td>
                               <td>
        <img src="${item.icon_url}" alt="img" width="50" class="rounded">
    </td>
                                <td>${highlight(item.title, keyword)}</td>
                                <td class="text-primary">${highlight(item.status, keyword)}</td>

                                <td>


                                    <a href="javascript:void(0)" data-id="${item.id}" 
                                       class="editApp btn btn-sm btn-warning light">
                                       <i class="fa fa-pencil"></i>
                                    </a>

                                    <a href="javascript:void(0)" data-id="${item.id}" 
                                       class="deleteContact btn btn-sm btn-danger light">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                        });

                    } else {
                        html = `
                        <tr>
                            <td colspan="6" class="text-center text-danger">
                                No related search
                            </td>
                        </tr>
                    `;
                    }

                    tableBody.innerHTML = html;
                });
        });
        function highlight(text, keyword) {
            if (!keyword) return text;

            const regex = new RegExp(`(${keyword})`, 'gi');
            return text.replace(regex, `<mark>$1</mark>`);
        }



    </script>
@endpush