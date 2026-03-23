{{-- resources/views/admin/pages/health-risks.blade.php --}}
@extends("admin.layout.admin-master")
@section("title", "Health Risks | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Health Risks</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-4 align-items-center justify-content-between">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="testSearch" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                        + Add Health Risk
                    </button>
                    <a href="javascript:void(0);" class="btn btn-danger btn-rounded deleteSelected">Delete Selected</a>

                </div>

            </div>

            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session("success") }}',
                        timer: 4000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            <!-- Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped bg-theme">
                                    <thead class="">
                                        <tr>
                                            <th>
                                                <div class="checkbox text-end align-self-center">
                                                    <div class="form-check custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input" id="checkAll"
                                                            required="">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </div>
                                            </th>
                                            {{-- <th>Icon</th> --}}
                                            <th>Title</th>
                                            {{-- <th>Slug</th> --}}
                                            {{-- <th>Parameters</th> --}}
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testTableBody">
                                        @forelse($healthRisks as $risk)
                                            <tr>
                                                <td>
                                                    <div class="checkbox text-end align-self-center ms-2">

                                                        <div class="form-check custom-checkbox ">
                                                            <input type="checkbox" class="form-check-input checkItem"
                                                                value="{{ $risk->id }}" required="">
                                                            <label class="form-check-label" for="checkbox"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                {{-- <td>
                                                    @if($risk->icon)
                                                    <img src="{{ asset('storage/' . $risk->icon) }}" width="40"
                                                        class="rounded-circle">
                                                    @else
                                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                                        style="width:40px;height:40px;">
                                                        <i class="fas fa-image "></i>
                                                    </div>
                                                    @endif
                                                </td> --}}
                                                <td class="">{{ Str::limit($risk->title, 25) }}</td>
                                                {{-- <td><code class="text-dark">{{ $risk->slug }}</code></td> --}}
                                                {{-- <td>
                                                    @php
                                                    $paramIds = is_array($risk->parameter_id) ? $risk->parameter_id :
                                                    json_decode($risk->parameter_id, true);
                                                    @endphp
                                                    @if($paramIds && count($paramIds))
                                                    @foreach(\App\Models\Parameter::find($paramIds) as $param)
                                                    @if($param)
                                                    <span class="badge bg-info  me-1 mb-1">{{ $param->title }}</span>
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    <em class="text-muted">—</em>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    <span
                                                        class="badge light badge-{{ $risk->status == 'active' ? 'success' : 'danger' }} px-3 py-2">
                                                        {{ ucfirst($risk->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info light me-1" data-bs-toggle="modal"
                                                        data-bs-target="#viewModal{{ $risk->id }}"><i
                                                            class="fas fa-eye"></i></button>
                                                    <button class="btn btn-sm btn-warning light me-1" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $risk->id }}"><i
                                                            class="fas fa-edit"></i></button>
                                                    <form action="{{ route('health-risks.destroy', $risk) }}" method="POST"
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
                                                <td colspan="7" class="text-center py-5 text-muted">No health risks found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php $parameters = \App\Models\Parameter::where('status', 'active')->orderBy('title')->get(); @endphp

    <!-- ADD MODAL -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('health-risks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Add New Health Risk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label>Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="add_title" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Slug <small class="text-muted">(auto-generated)</small></label>
                                <input type="text" name="slug" id="add_slug" class="form-control" readonly>
                            </div>

                            <div class="col-md-4">
                                <label>Icon/Image <span class="text-danger">*</span></label>
                                <input type="file" name="icon" class="form-control" accept="image/*" required>
                            </div>

                            <div class="col-md-8">
                                <label>Related Parameters <span class="text-danger">*</span></label>
                                <select name="parameter_id[]" multiple class="form-control" style="height:180px;" required>
                                    @foreach($parameters as $param)
                                        <option value="{{ $param->id }}">{{ $param->title }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                            </div>

                            <div class="col-md-4">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label>Description</label>
                                <textarea name="description" id="add_description" class="summernote"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- VIEW & EDIT MODALS -->
    @foreach($healthRisks as $risk)
        <!-- VIEW MODAL -->
        <div class="modal fade" id="viewModal{{ $risk->id }}">
            <div class="modal-dialog custom-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Health Risk Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <table class="table table-bordered table-striped mb-0">
                        <tr>

                            <th>Icon :</th>
                            <td>
                                @if($risk->icon)
                                    <img src="{{ asset('storage/' . $risk->icon) }}" width="100" class="rounded">
                                @else
                                    <em class="text-muted">No icon</em>
                                @endif
                            </td>
                            <th width="180">Title :</th>
                            <td>{{ $risk->title }}</td>
                        </tr>
                        <tr>
                            <th>Status :</th>
                            <td>
                                <span class="badge bg-{{ $risk->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($risk->status) }}
                                </span>
                            </td>
                            <th>Created :</th>
                            <td>{{ $risk->created_at->format('d M Y, h:i A') }}</td>
                        </tr>

                        <tr>
                            <th>Related Parameters :</th>
                            <td colspan="3">
                                @php $paramIds = is_array($risk->parameter_id) ? $risk->parameter_id : json_decode($risk->parameter_id, true); @endphp
                                @if($paramIds && count($paramIds))
                                    @foreach(\App\Models\Parameter::find($paramIds) as $param)
                                        @if($param)<span class="badge bg-primary me-1 mb-1">{{ $param->title }}</span>@endif
                                    @endforeach
                                @else
                                    <em class="text-muted">None</em>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td colspan="3"> {!! $risk->description ?: '<em class="text-muted">No description.</em>' !!}</td>
                        </tr>
                    </table>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div class="modal fade" id="editModal{{ $risk->id }}">
            <div class="modal-dialog custom-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit: {{ $risk->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('health-risks.update', $risk) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label>Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $risk->title) }}"
                                        class="form-control edit-title" data-slug="#edit_slug_{{ $risk->id }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Slug</label>
                                    <input type="text" name="slug" id="edit_slug_{{ $risk->id }}"
                                        value="{{ old('slug', $risk->slug) }}" class="form-control">
                                    <small class="text-muted">You can edit if needed</small>
                                </div>

                                <div class="col-md-4">
                                    <label>Icon/Image</label>
                                    <input type="file" name="icon" class="form-control" accept="image/*">
                                    @if($risk->icon)
                                        <img src="{{ asset('storage/' . $risk->icon) }}" class="mt-2 rounded" width="80">
                                    @endif
                                </div>

                                <div class="col-md-8">
                                    <label>Related Parameters <span class="text-danger">*</span></label>
                                    <select name="parameter_id[]" multiple class="form-control" style="height:180px;" required>
                                        @php
                                            $selectedIds = is_array($risk->parameter_id) ? $risk->parameter_id : json_decode($risk->parameter_id, true);
                                        @endphp
                                        @foreach($parameters as $param)
                                            <option value="{{ $param->id }}" {{ is_array($selectedIds) && in_array($param->id, $selectedIds) ? 'selected' : '' }}>
                                                {{ $param->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $risk->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $risk->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label>Description</label>
                                    <textarea name="description" id="edit_description_{{ $risk->id }}"
                                        class="summernote">{!! old('description', $risk->description) !!}</textarea>
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
            // Summernote
            $('#add_description').summernote({ placeholder: 'Write description...', height: 200 });

            // Auto generate slug from title
            $('#add_title').on('input', function () {
                let slug = $(this).val().toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
                $('#add_slug').val(slug);
            });

            // For edit modals
            $('.edit-title').on('input', function () {
                let target = $(this).data('slug');
                let slug = $(this).val().toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
                $(target).val(slug);
            });

            // Re-init Summernote on edit modal open
            $('body').on('shown.bs.modal', function (e) {
                if (e.target.id.includes('editModal')) {
                    const id = e.target.id.replace('editModal', '');
                    $(`#edit_description_${id}`).summernote({
                        placeholder: 'Write description...',
                        height: 200
                    });
                }
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
                Swal.fire("Oops!", "Please select at least one Health Risk.", "warning");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Selected Health Risk will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete selected!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "/health-risks/delete-selected",

                        type: "POST",
                        data: {
                            ids: selected,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            Swal.fire("Deleted!", "Selected Health Risk removed.", "success");

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

            fetch(`/health-risks/search?keyword=${keyword}`)
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


        $(function () {
            $('.delete-btn').click(function (e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete?',
                    text: "This Health Risk will be permanently  deleted!",
                    icon: 'warning',
                    showCancelButton: true, confirmButtonText: 'Yes, delete!'
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });
    </script>
@endpush