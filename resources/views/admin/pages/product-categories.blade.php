@extends('admin.layout.admin-master')

@section('title', 'Product Categories | VSR')

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Product Categories</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-end">
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                    + Add Category
                </button>
            </div>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 4000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error!',
                        html: '@foreach ($errors->all() as $error) {{ $error }}<br> @endforeach',
                        timer: 5000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Category Name</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($cat->image)
                                                <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}"
                                                    class="rounded" width="50" height="50"
                                                    style="object-fit: cover;">
                                            @else
                                                <span class="text-muted small">No Image</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $cat->name }}</strong></td>

                                        <td>
                                            @if ($cat->status == 1)
                                                <span class="badge bg-success light">Active</span>
                                            @else
                                                <span class="badge bg-danger light">Inactive</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $cat->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('product-categories.destroy', $cat) }}" method="POST"
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
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            No categories found. Click "+ Add Category" to create one.
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

    <!-- ✅ Add Modal (with Image Upload) -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-centered">
            <form action="{{ route('product-categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5>Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        {{-- Image Upload Field --}}
                        <div class="mb-3">
                            <label>Category Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" id="addImageInput">
                            <small class="text-muted">Allowed: JPG, PNG, JPEG (Max: 2MB)</small>
                            {{-- Image Preview --}}
                            <div id="addImagePreview" class="mt-2 d-none">
                                <img src="" alt="Preview" class="rounded" width="100" height="100"
                                    style="object-fit: cover;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ Edit Modals (with Image Upload + Preview) -->
    @foreach ($categories as $cat)
        <div class="modal fade" id="edit{{ $cat->id }}">
            <div class="modal-dialog modal-centered">
                <form action="{{ route('product-categories.update', $cat) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-theme-light">
                            <h5>Edit: {{ $cat->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $cat->name) }}"
                                    class="form-control" required>
                            </div>

                            {{-- Current Image Display --}}
                            <div class="mb-3">
                                <label>Current Image</label><br>
                                @if ($cat->image)
                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}"
                                        class="rounded mb-2" width="100" height="100" style="object-fit: cover;">
                                    <p class="text-muted small mb-0">Current: {{ basename($cat->image) }}</p>
                                @else
                                    <span class="text-muted small">No image uploaded</span>
                                @endif
                            </div>

                            {{-- New Image Upload --}}
                            <div class="mb-3">
                                <label>Change Image <small class="text-muted">(Optional)</small></label>
                                <input type="file" name="image" class="form-control" accept="image/*"
                                    id="editImageInput{{ $cat->id }}">
                                <small class="text-muted">Leave empty to keep current image</small>
                                {{-- Preview for new image --}}
                                <div id="editImagePreview{{ $cat->id }}" class="mt-2 d-none">
                                    <img src="" alt="Preview" class="rounded" width="100" height="100"
                                        style="object-fit: cover;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $cat->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $cat->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(function() {
            // 🔹 Add Modal Image Preview
            $('#addImageInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#addImagePreview img').attr('src', e.target.result);
                        $('#addImagePreview').removeClass('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // 🔹 Edit Modal Image Preview (Dynamic for each category)
            @foreach ($categories as $cat)
                $('#editImageInput{{ $cat->id }}').on('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#editImagePreview{{ $cat->id }} img').attr('src', e.target.result);
                            $('#editImagePreview{{ $cat->id }}').removeClass('d-none');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            @endforeach

            // 🔹 Search Functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#categoryTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // 🔹 SweetAlert2 Delete Confirmation
            $('.delete-btn').click(function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete Category?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
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
