{{-- resources/views/admin/pages/gallery.blade.php --}}
@extends("admin.layout.admin-master")
@section("title", "Gallery | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">

            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Gallery Images</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-end">

                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addImageModal">
                    + Add Image
                </button>
            </div>

            @if(session('success'))
                <script>Swal.fire('Success!', '{{ session('success') }}', 'success');</script>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="galleryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gallery as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded" width="80"
                                                height="60" style="object-fit: cover;">
                                        </td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $item->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{-- <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button> --}}
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('gallery.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger light delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">

                                            No images in gallery yet.
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
    <div class="modal fade" id="addImageModal">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Add New Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label>Image <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <small class="text-muted">Recommended: 576x576px • Max 2MB</small>
                            </div>
                            <div class="col-12">
                                <label>Caption / Alt Text (Optional)</label>
                                <input type="text" name="caption" class="form-control"
                                    placeholder="e.g. Continuity Care – Advanced Diagnostic Excellence">
                            </div>
                            <div class="col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Image</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View & Edit Modals -->
    @foreach($gallery as $item)
        <!-- View Modal -->
        <div class="modal fade" id="view{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog custom-modal modal-centered">
                <div class="modal-content">

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Image Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Table Layout -->
                    <table class="table table-bordered table-striped mb-0">
                        <tr>
                            <th>Caption :</th>
                            <td>{{ $item->caption ?: '—' }}</td>

                            <th>Status :</th>
                            <td>
                                <span class="badge bg-{{ $item->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th> Image</th>
                            <td><img src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded"
                                    style="max-height: 60vh; object-fit: contain;"></td>
                        </tr>

                    </table>



                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Edit Modal -->
        <div class="modal fade" id="edit{{ $item->id }}">
            <div class="modal-dialog custom-modal">
                <form action="{{ route('gallery.update', $item) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit Image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class=" mb-4">
                                <img src="{{ asset('storage/' . $item->image) }}" class="rounded" width="100" height="100">
                                <p class="text-muted mt-2">Current Image</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label>Change Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                                <div class="col-12">
                                    <label>Caption / Alt Text</label>
                                    <input type="text" name="caption" value="{{ old('caption', $item->caption) }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $item->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Image</button>
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
            // Search
            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $("#galleryTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // SweetAlert2 Delete Confirmation (same as FAQs)
            $('.delete-btn').click(function (e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Delete Image?',
                    text: "This Gallery image will be permanently deleted !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush