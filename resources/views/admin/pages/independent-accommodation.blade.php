{{-- resources/views/admin/pages/parameter.blade.php --}}
@extends("admin.layout.admin-master")
@section("title", "SIL Accommodations | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">SIL Accommodations</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="testSearch" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addParameterModal">
                        + Add Accommodation
                    </button>
                    <a href="javascript:void(0);" class="btn btn-danger btn-rounded deleteSelected">Delete Selected</a>

                </div>
            </div>

            @if(session('success'))
                <script>
                    Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session("success") }}', timer: 4000 });
                </script>
            @endif


            <!-- Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped" id="example5">
                                    <thead>
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
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testTableBody">
                                        @forelse($IndependentAccommodations as $param)
                                            <tr>
                                                <td>
                                                    <div class="checkbox text-end align-self-center ms-2">

                                                        <div class="form-check custom-checkbox ">
                                                            <input type="checkbox" class="form-check-input checkItem"
                                                                value="{{ $param->id }}" required="">
                                                            <label class="form-check-label" for="checkbox"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($param->image)
                                                        <img src="{{ $param->image ? asset('storage/' . $param->image) : asset('admin/images/default.webp') }}"
                                                            width="40" height="40" class="rounded border" alt="image">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                            style="width:40px;height:40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($param->title, 40) }}</td>

                                                <td>
                                                    <span
                                                        class="badge light badge-{{ $param->status == 'active' ? 'success' : 'danger' }} px-3 py-2">
                                                        {{ ucfirst($param->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info light me-1" data-bs-toggle="modal"
                                                        data-bs-target="#viewModal{{ $param->id }}" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning light me-1" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $param->id }}" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger light delete-param"
                                                        data-id="{{ $param->id }}" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">No accommodation found.</td>
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


    <!-- ADD MODAL -->
    <div class="modal fade" id="addParameterModal" tabindex="-1">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('independent-accommodation.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5 class="modal-title">Add New Accommodation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Enter parameter title"
                                    required>
                            </div>


                            <div class="col-md-6">
                                <label class="form-label">Image </label>
                                <input type="file" name="image" class="form-control" accept="image/*,.svg">
                                <small class="text-muted">Recommended: SVG or PNG </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="add_description" class="summernote"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Overview</label>
                                <textarea name="overview" id="add_overview" class="summernote"></textarea>
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

    <!-- VIEW MODAL -->
    @foreach($IndependentAccommodations as $param)
        <div class="modal fade" id="viewModal{{ $param->id }}">
            <div class="modal-dialog custom-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Accommodation Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <table class="table table-bordered table-striped mb-0">
                        <tr>
                            <th>Image:</th>
                            <td>
                                @if($param->image)
                                    <img src="{{ $param->image ? asset('storage/' . $param->image) : asset('admin/images/default.webp') }}"
                                        width="80" class="rounded border">
                                @else
                                    <em class="text-muted">No image</em>
                                @endif
                            </td>

                            <th>Title:</th>
                            <td>{{ $param->title }}</td>

                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td colspan="3">
                                <span class="badge bg-{{ $param->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($param->status) }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Description</th>

                            <td colspan="3">{!! $param->description ?: '<em class="text-muted">No description</em>' !!}</td>
                        </tr>
                        <tr>
                            <th>Overview</th>
                            <td colspan="3">{!! $param->overview ?: '<em class="text-muted">No overview</em>' !!}</td>
                        </tr>
                    </table>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- EDIT MODAL -->
    @foreach($IndependentAccommodations as $param)
        <div class="modal fade" id="editModal{{ $param->id }}">
            <div class="modal-dialog custom-modal">
                <form action="{{ route('independent-accommodation.update', $param) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-theme-light">
                            <h5 class="modal-title">Edit Accommodation: {{ $param->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $param->title) }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $param->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $param->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Image</label>
                                    @if($param->image)
                                        <div class="mb-2">
                                            <img src="{{ $param->image ? asset('storage/' . $param->image) : asset('admin/images/default.webp') }}"
                                                width="60" class="rounded border">
                                        </div>
                                    @endif
                                    <input type="file" name="image" class="form-control" accept="image/*,.svg">
                                    <small class="text-muted">Leave empty to keep current</small>
                                </div>





                                <div class="col-12">
                                    <label>Description</label>
                                    <textarea name="description" id="edit_description_{{ $param->id }}"
                                        class="summernote">{!! old('description', $param->description) !!}</textarea>
                                </div>
                                <div class="col-12">
                                    <label>Overview</label>
                                    <textarea name="overview" id="edit_overview_{{ $param->id }}"
                                        class="summernote">{!! old('overview', $param->overview) !!}</textarea>
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
            $('#add_description, #add_overview').summernote({
                placeholder: 'Write here...',
                tabsize: 2,
                height: 100
            });

            $('[id^="edit_description_"], [id^="edit_overview_"]').summernote({
                placeholder: 'Write here...',
                tabsize: 2,
                height: 200
            });
        });




        $(document).on('click', '.delete-param', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This Accommodation will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("independent-accommodation.destroy", ":id") }}'.replace(':id', id);

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
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
                Swal.fire("Oops!", "Please select at least one accommodation.", "warning");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Selected accommodation will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete selected!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('independent-accommodation.delete-selected') }}",


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
        // const searchInput = document.getElementById('testSearch');
        // const tableBody = document.getElementById('testTableBody');
        // searchInput.addEventListener('keyup', function () {

        //     let keyword = this.value.trim();

        //     fetch(`/parameters/search?keyword=${keyword}`)
        //         .then(res => res.json())
        //         .then(res => {

        //             let html = '';

        //             if (res.data.length > 0) {

        //                 res.data.forEach(item => {
        //                     html += `
        //                                     <tr>
        //                                         <td>
        //                                             <input type="checkbox" class="checkItem" value="${item.id}">
        //                                         </td>
        //                                        <td>
        //                 <img src="${item.icon_url}" alt="img" width="50" class="rounded">
        //             </td>
        //                                         <td>${highlight(item.title, keyword)}</td>
        //                                         <td>${highlight(item.price, keyword)}</td>
        //                                         <td class="text-primary">${highlight(item.status, keyword)}</td>

        //                                         <td>


        //                                             <a href="javascript:void(0)" data-id="${item.id}"
        //                                                class="editApp btn btn-sm btn-warning light">
        //                                                <i class="fa fa-pencil"></i>
        //                                             </a>

        //                                             <a href="javascript:void(0)" data-id="${item.id}"
        //                                                class="deleteContact btn btn-sm btn-danger light">
        //                                                <i class="fa fa-trash"></i>
        //                                             </a>
        //                                         </td>
        //                                     </tr>
        //                                 `;
        //                 });

        //             } else {
        //                 html = `
        //                                 <tr>
        //                                     <td colspan="6" class="text-center text-danger">
        //                                         No related search
        //                                     </td>
        //                                 </tr>
        //                             `;
        //             }

        //             tableBody.innerHTML = html;
        //         });
        // });
        // function highlight(text, keyword) {
        //     if (!keyword) return text;

        //     const regex = new RegExp(`(${keyword})`, 'gi');
        //     return text.replace(regex, `<mark>$1</mark>`);
        // }

    </script>
@endpush