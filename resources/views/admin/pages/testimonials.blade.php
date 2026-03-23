@extends("admin.layout.admin-master")
@section("title", "Testimonials | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Testimonials</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <div class="input-group search-area w-25">
                    {{-- <input type="text" id="searchInput" class="form-control" placeholder="Search testimonials...">
                    <span class="input-group-text"><i class="flaticon-381-search-2"></i></span> --}}
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                    + Add Testimonial
                </button>
            </div>

            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session("success") }}',
                        timer: 4000,
                        timerProgressBar: true
                    });
                </script>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="testimonialTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($testimonials as $testimonial)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($testimonial->image)
                                                <img src="{{ asset('storage/' . $testimonial->image) }}" width="50"
                                                    class="rounded-circle">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width:50px;height:50px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td><strong>{{ $testimonial->name }}</strong></td>
                                        <td>{{ $testimonial->designation }}</td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $testimonial->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($testimonial->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $testimonial->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $testimonial->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger light delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            No testimonials found. Click "+ Add Testimonial" to create one.
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
    <div class="modal fade" id="addModal">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5>Add New Testimonial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" placeholder="Enter Testimonial Name" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label>Designation <span class="text-danger">*</span></label>
                                <input type="text" name="designation" placeholder="Designation" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label>Photo <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                            <div class="col-md-6">
                                <label>Side Photo <span class="text-danger">*</span></label>
                                <input type="file" name="photo" class="form-control" accept="image/*" required>
                            </div>
                            <div class="col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Quote<span class="text-danger">(Max Words:6-7)*</span></label>
                                <input type="text" name="quote" placeholder="Best Services" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label>Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control summernote" rows="4" required></textarea>
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

    <!-- View & Edit Modals -->
    @foreach($testimonials as $testimonial)
        <!-- View Modal -->
        <div class="modal fade" id="view{{ $testimonial->id }}" tabindex="-1">
            <div class="modal-dialog custom-modal modal-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Testimonial Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Table Layout -->
                    <table class="table table-bordered table-striped mb-0">

                        <tr>
                            <th>Name :</th>
                            <td>{{ $testimonial->name }}</td>

                            <th>Status :</th>
                            <td>
                                <span class="badge bg-{{ $testimonial->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($testimonial->status) }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Designation :</th>
                            <td>
                                {{ $testimonial->designation ?? '—' }}
                            </td>
                            <th>Quote</th>
                            <td>
                                {{ $testimonial->quote }}
                            </td>
                        </tr>

                        <tr>
                            <th>Image :</th>
                            <td>
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" width="120"
                                        class="rounded-circle mb-2">
                                @else
                                    <em class="text-muted">No image uploaded</em>
                                @endif
                            </td>
                            <th>Side Image :</th>
                            <td>
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" width="120"
                                        class="rounded-circle mb-2">
                                @else
                                    <em class="text-muted">No image uploaded</em>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Message :</th>
                            <td colspan="3">{!! $testimonial->message !!}</td>
                        </tr>

                    </table>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Edit Modal -->
        <div class="modal fade" id="edit{{ $testimonial->id }}">
            <div class="modal-dialog custom-modal">
                <form action="{{ route('testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-theme-light">
                            <h5>Edit Testimonial</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{ $testimonial->name }}" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label>Designation</label>
                                    <input type="text" name="designation" value="{{ $testimonial->designation }}"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Change Photo</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    @if($testimonial->image)
                                        <img src="{{ asset('storage/' . $testimonial->image) }}" width="80"
                                            class="mt-2 rounded-circle">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label>Change Side Photo</label>
                                    <input type="file" name="photo" class="form-control" accept="image/*">
                                    @if($testimonial->photo)
                                        <img src="{{ asset('storage/' . $testimonial->photo) }}" width="120"
                                            class="mt-2 ">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label>Quote</label>
                                    <input type="text" name="quote" value="{{ $testimonial->quote }}" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $testimonial->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $testimonial->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label>Message</label>
                                    <textarea name="message" class="form-control summernote" rows="4"
                                        required>{{ $testimonial->message }}</textarea>
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

            $('.summernote').summernote({
                height: 200,
                placeholder: 'Write the message here...'
            });
            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $("#testimonialTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('.delete-btn').click(function (e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete Testimonial?',
                    text: "This testimonial will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush