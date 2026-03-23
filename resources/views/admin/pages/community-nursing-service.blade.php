@extends("admin.layout.admin-master")
@section("title", "Community Nursing Service | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Community Nursing Service</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <div class="input-group search-area w-25">
                    {{-- <input type="text" id="searchInput" class="form-control" placeholder="Search testimonials...">
                    <span class="input-group-text"><i class="flaticon-381-search-2"></i></span> --}}
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                    + Add Service
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
                                    <th>title</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($CommunityNursingServiceCard as $card)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($card->image)
                                                <img src="{{ asset('storage/' . $card->image) }}" width="50" class="rounded-circle">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width:50px;height:50px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td><strong>{{ $card->title }}</strong></td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $card->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($card->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $card->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $card->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('community-nursing-service.destroy', $card) }}" method="POST"
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
                                            No Section Card found. Click "+ Add section" to create one.
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
        <div class="modal-dialog custom-modal bg-theme-light">
            <form action="{{ route('community-nursing-service.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5>Add New Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>title <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Enter Title " class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>Icon <span class="text-danger">(60*60)*</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>

                            <div class="col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control summernote" rows="4" required></textarea>
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
    @foreach($CommunityNursingServiceCard as $card)
        <!-- View Modal -->
        <div class="modal fade" id="view{{ $card->id }}" tabindex="-1">
            <div class="modal-dialog custom-modal modal-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Section Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Table Layout -->
                    <table class="table table-bordered table-striped mb-0">

                        <tr>
                            <th>Title :</th>
                            <td>{{ $card->title }}</td>

                            <th>Status :</th>
                            <td>
                                <span class="badge bg-{{ $card->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($card->status) }}
                                </span>
                            </td>
                        </tr>



                        <tr>
                            <th>Image :</th>
                            <td>
                                @if($card->image)
                                    <img src="{{ asset('storage/' . $card->image) }}" width="120" class=" mb-2">
                                @else
                                    <em class="text-muted">No image uploaded</em>
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <th>Description :</th>
                            <td colspan="3">{!! $card->description !!}</td>
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
        <div class="modal fade" id="edit{{ $card->id }}">
            <div class="modal-dialog custom-modal">
                <form action="{{ route('community-nursing-service.update', $card) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-theme-light">
                            <h5>Edit section</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Title</label>
                                    <input type="text" name="name" value="{{ $card->title }}" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label>Change Photo</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    @if($card->image)
                                        <img src="{{ asset('storage/' . $card->image) }}" width="80" class="mt-2 rounded-circle">
                                    @endif
                                </div>


                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $card->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $card->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control summernote" rows="4"
                                        required>{{ $card->description }}</textarea>
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
                    title: 'Delete Section?',
                    text: "This Section Card will be permanently deleted!",
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