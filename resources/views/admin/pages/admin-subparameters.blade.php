{{-- resources/views/admin/pages/admin-subparameters.blade.php --}}
@extends("admin.layout.admin-master")

@section("title", " Test Packages | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">

            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Health Packages</li>
                </ol>
            </div>

            <!-- Header -->
            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="testSearch" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                        + Add Health Package
                    </button>
                    <a href="javascript:void(0);" class="btn btn-danger btn-rounded deleteSelected">Delete Selected</a>

                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <script>Swal.fire('Success!', '{{ session('success') }}', 'success');</script>
            @endif

            <!-- Table -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="packageTable">
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
                                    <th>Title</th>
                                    {{-- <th>Main Parameters</th> --}}
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="testTableBody">
                                @forelse($subparameters as $index => $sub)
                                    <tr>
                                        <td>
                                            <div class="checkbox text-end align-self-center ms-2">

                                                <div class="form-check custom-checkbox ">
                                                    <input type="checkbox" class="form-check-input checkItem"
                                                        value="{{ $sub->id }}" required="">
                                                    <label class="form-check-label" for="checkbox"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ Str::limit($sub->title, 40) }}</td>
                                        {{-- <td>
                                            @forelse($sub->parameters as $param)
                                            <span class="badge bg-info me-1">{{ $param->title }}</span>
                                            @empty
                                            <span class="text-muted">—</span>
                                            @endforelse
                                        </td> --}}
                                        <td><strong>₹{{ number_format($sub->price ?? 0, 2) }}</strong></td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $sub->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $sub->id }}" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $sub->id }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin-subparameters.destroy', $sub) }}" method="POST"
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
                                        <td colspan="6" class="text-center py-5 text-muted">No health packages found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Load Data --}}
    @php
        $parameters = \App\Models\Parameter::where('status', 'active')->orderBy('title')->get();
        $tests = \App\Models\Test::orderBy('title')->get();
    @endphp

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addModal">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('admin-subparameters.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Health Package</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Price (₹)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="0">
                            </div>
                            <div class="col-md-6">
                                <label>Main Parameters <span class="text-danger">*</span></label>
                                <select name="parameter_id[]" multiple class="form-control" style="height:200px;" required>
                                    @foreach($parameters as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                            <div class="col-md-6">
                                <label>Linked Tests</label>
                                <select name="test_ids[]" multiple class="form-control" style="height:200px;">
                                    @foreach($tests as $test)
                                        <option value="{{ $test->id }}">{{ $test->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label>Description</label>
                                <textarea name="description" class="summernote"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Package</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- VIEW & EDIT MODALS --}}
    @foreach($subparameters as $sub)
        <!-- View Modal -->
        <div class="modal fade" id="view{{ $sub->id }}" tabindex="-1">
            <div class="modal-dialog custom-modal modal-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Package Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Table Layout -->
                    <table class="table table-bordered table-striped mb-0">

                        <tr>
                            <th>Title :</th>
                            <td colspan="3">{{ $sub->title }}</td>


                        </tr>

                        <tr>
                            <th>Price :</th>
                            <td><strong>₹{{ number_format($sub->price ?? 0, 2) }}</strong></td>

                            <th>Status :</th>
                            <td>
                                <span class="badge bg-{{ $sub->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Parameters :</th>
                            <td colspan="3">
                                @forelse($sub->parameters as $p)
                                    <span class="badge bg-info me-1 mb-1">{{ $p->title }}</span>
                                @empty
                                    <em class="text-muted">No parameters linked</em>
                                @endforelse
                            </td>
                        </tr>

                        @if($sub->tests->count())
                            <tr>
                                <th>Tests :</th>
                                <td colspan="3">
                                    @foreach($sub->tests as $t)
                                        <span class="badge bg-primary me-1 mb-1">{{ $t->title }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <th>Image :</th>
                            <td colspan="3">
                                @if($sub->image)
                                    <img src="{{ Storage::url($sub->image) }}" class="img-fluid rounded" style="max-height:100px;">
                                @else
                                    <em class="text-muted">No image uploaded</em>
                                @endif
                            </td>
                        </tr>
                        @if($sub->description)
                            <tr>
                                <th>Description</th>
                                <td colspan="3">{!! $sub->description !!}</td>
                            </tr>
                        @endif

                    </table>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Edit Modal -->
        <div class="modal fade" id="edit{{ $sub->id }}">
            <div class="modal-dialog custom-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit {{ $sub->title }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin-subparameters.update', $sub) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6"><label>Title *</label><input type="text" name="title"
                                        value="{{ $sub->title }}" class="form-control" required></div>
                                <div class="col-md-6"><label>Price</label><input type="number" name="price"
                                        value="{{ $sub->price ?? 0 }}" class="form-control"></div>

                                <!-- FIXED: Main Parameters -->
                                <div class="col-md-6">
                                    <label>Main Parameters <span class="text-danger">*</span></label>
                                    <select name="parameter_id[]" multiple class="form-control" style="height:200px;" required>
                                        @foreach($parameters as $p)
                                            <option value="{{ $p->id }}" {{ is_array($sub->parameter_id) && in_array($p->id, $sub->parameter_id) ? 'selected' : '' }}>
                                                {{ $p->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                                </div>

                                <!-- Linked Tests (Correct) -->
                                <div class="col-md-6">
                                    <label>Linked Tests</label>
                                    <select name="test_ids[]" multiple class="form-control" style="height:200px;">
                                        @foreach($tests as $t)
                                            <option value="{{ $t->id }}" {{ $sub->tests->contains($t->id) ? 'selected' : '' }}>
                                                {{ $t->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Image</label>
                                    <input type="file" name="image" class="form-control">
                                    @if($sub->image)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($sub->image) }}" class="rounded" style="max-height:100px;">
                                            <small class="text-muted d-block">Current image</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $sub->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $sub->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label>Description</label>
                                    <textarea name="description" class="summernote">{{ $sub->description }}</textarea>
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
            $('.summernote').summernote({ height: 200 });
            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $("#packageTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $('.delete-btn').click(function (e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete?',
                    text: "This Package will be permanently  deleted!",
                    icon: 'warning',
                    showCancelButton: true, confirmButtonText: 'Yes, delete!'
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });

        // DELETE SELECTED
        $('.deleteSelected').click(function () {

            let selected = [];

            $(".checkItem:checked").each(function () {
                selected.push($(this).val());
            });

            console.log("Selected IDs:", selected); // debug

            if (selected.length === 0) {
                Swal.fire("Oops!", "Please select at least one sub parameter.", "warning");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Selected Sub Parameter will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete selected!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "/admin-subparameters/delete-selected",

                        type: "POST",
                        data: {
                            ids: selected,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            Swal.fire("Deleted!", "Selected Sub parameter removed.", "success");

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

        const searchInput = document.getElementById('testSearch');
        const tableBody = document.getElementById('testTableBody');
        searchInput.addEventListener('keyup', function () {

            let keyword = this.value.trim();

            fetch(`/admin-subparameters/search?keyword=${keyword}`)
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

                                <td>${highlight(item.title, keyword)}</td>
                                <td>${highlight(item.price, keyword)}</td>
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