@extends('admin.layout.admin-master')

@section('title', 'Products | VSR')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <h4 class="text-black font-w600">All Products</h4>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                    + Add Product
                </button>
            </div>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: '@foreach ($errors->all() as $error) {{ $error }}<br> @endforeach',
                        timer: 5000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($product->first_image)
                                                <img src="{{ asset('storage/' . $product->first_image) }}"
                                                    alt="{{ $product->name }}" width="50" height="50" class="rounded"
                                                    style="object-fit: cover;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $product->name }}</strong></td>
                                        <td><code>{{ $product->sku }}</code></td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($product->discount_price)
                                                <del class="text-muted">Rs.{{ number_format($product->price, 2) }}</del><br>
                                                <span
                                                    class="text-success">Rs.{{ number_format($product->discount_price, 2) }}</span>
                                            @else
                                                ${{ number_format($product->price, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $product->status ? 'success' : 'danger' }}">
                                                {{ $product->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <!-- View Button -->
                                            <button class="btn btn-sm btn-info light view-btn" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $product->id }}"
                                                data-product='@json($product)'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <!-- Edit Button -->
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $product->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <!-- Delete Form -->
                                            <form action="{{ route('products.destroy', $product) }}" method="POST"
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
                                        <td colspan="9" class="text-center py-5 text-muted">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- ========== View Modals (Outside Table) ========== -->
                        @foreach ($products as $product)
                            <div class="modal fade" id="view{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header bg-theme-light">
                                            <h5 class="modal-title">Product Details: {{ $product->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-6 mb-4">
                                                    <h6>Product Images</h6>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        @foreach ($product->images ?? [] as $image)
                                                            <img src="{{ asset('storage/' . $image) }}"
                                                                alt="{{ $product->name }}" class="rounded" width="100"
                                                                height="100" style="object-fit: cover;">
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th width="40%">Name</th>
                                                            <td>{{ $product->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>SKU</th>
                                                            <td><code>{{ $product->sku }}</code></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Category</th>
                                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Price</th>
                                                            <td>
                                                                @if ($product->discount_price)
                                                                    <del>Rs.{{ number_format($product->price, 2) }}</del>
                                                                    <span
                                                                        class="text-success ms-2">Rs.{{ number_format($product->discount_price, 2) }}</span>
                                                                @else
                                                                    ${{ number_format($product->price, 2) }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Stock</th>
                                                            <td>{{ $product->stock }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td><span
                                                                    class="badge badge-{{ $product->status ? 'success' : 'danger' }}">{{ $product->status ? 'Active' : 'Inactive' }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Featured</th>
                                                            <td>{{ $product->featured ? 'Yes' : 'No' }}</td>
                                                        </tr>
                                                        @if ($product->size)
                                                            <tr>
                                                                <th>Size</th>
                                                                <td>{{ $product->size }}</td>
                                                            </tr>
                                                        @endif
                                                        @if ($product->brand)
                                                            <tr>
                                                                <th>Brand</th>
                                                                <td>{{ $product->brand }}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                            @if ($product->description)
                                                <div class="mt-4">
                                                    <h6>Description</h6>
                                                    <p>{{ $product->description }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ========== Edit Modals (Outside Table) ========== -->
                            <!-- Edit Modal -->
                            <div class="modal fade" id="edit{{ $product->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <form action="{{ route('products.update', $product) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header bg-theme-light">
                                                <h5 class="modal-title">Edit Product: {{ $product->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h6 class="mb-3">Product Information</h6>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label>Product Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" class="form-control"
                                                                    value="{{ old('name', $product->name) }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label>SKU <span class="text-danger">*</span></label>
                                                                <input type="text" name="sku" class="form-control"
                                                                    value="{{ old('sku', $product->sku) }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Category <span class="text-danger">*</span></label>
                                                            <select name="category_id" class="form-control" required>
                                                                <option value="">Select Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Short Description</label>
                                                            <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Description</label>
                                                            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                                                        </div>

                                                        <!-- Current Images -->
                                                        <div class="mb-3">
                                                            <label>Current Images</label>
                                                            <div class="d-flex gap-2 flex-wrap"
                                                                id="currentImages{{ $product->id }}">
                                                                @foreach ($product->images ?? [] as $index => $image)
                                                                    <div class="position-relative">
                                                                        <img src="{{ asset('storage/' . $image) }}"
                                                                            alt="{{ $product->name }}" class="rounded"
                                                                            width="80" height="80"
                                                                            style="object-fit: cover;">
                                                                        <input type="hidden" name="remove_images[]"
                                                                            value="{{ $index }}"
                                                                            class="remove-img-{{ $index }}"
                                                                            style="display:none;">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm position-absolute top-0 start-100 translate-middle rounded-circle"
                                                                            onclick="removeImage({{ $product->id }}, {{ $index }})"
                                                                            style="width: 20px; height: 20px; padding: 0; font-size: 12px;">
                                                                            ×
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Add New Images</label>
                                                            <input type="file" name="images[]" class="form-control"
                                                                multiple accept="image/*"
                                                                id="editImages{{ $product->id }}">
                                                            <small class="text-muted">Leave empty to keep current
                                                                images</small>
                                                            <div id="editImagePreview{{ $product->id }}"
                                                                class="mt-2 d-flex gap-2 flex-wrap"></div>
                                                        </div>

                                                        <h6 class="mb-3 mt-4">Additional Information</h6>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label>Size/Volume</label>
                                                                <input type="text" name="size" class="form-control"
                                                                    value="{{ old('size', $product->size) }}"
                                                                    placeholder="e.g., 800 ML">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label>Brand</label>
                                                                <input type="text" name="brand" class="form-control"
                                                                    value="{{ old('brand', $product->brand) }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">

                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <div class="mb-3">
                                                                    <label>Status</label>
                                                                    <select name="status" class="form-control">
                                                                        <option value="1"
                                                                            {{ old('status', $product->status) == 1 ? 'selected' : '' }}>
                                                                            Active</option>
                                                                        <option value="0"
                                                                            {{ old('status', $product->status) == 0 ? 'selected' : '' }}>
                                                                            Inactive</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" name="featured"
                                                                            class="form-check-input" value="1"
                                                                            {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Featured
                                                                            Product</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <div class="mb-3">
                                                                    <label>Regular Price <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">$</span>
                                                                        <input type="number" name="price"
                                                                            class="form-control" step="0.01"
                                                                            value="{{ old('price', $product->price) }}"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label>Discount Price</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">$</span>
                                                                        <input type="number" name="discount_price"
                                                                            class="form-control" step="0.01"
                                                                            value="{{ old('discount_price', $product->discount_price) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <div class="mb-3">
                                                                    <label>Stock Quantity <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" name="stock"
                                                                        class="form-control"
                                                                        value="{{ old('stock', $product->stock) }}"
                                                                        min="0" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" name="in_stock"
                                                                            class="form-check-input" value="1"
                                                                            {{ old('in_stock', $product->in_stock) ? 'checked' : '' }}
                                                                            {{ $product->in_stock ? 'checked' : '' }}>
                                                                        <label class="form-check-label">In Stock</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Product</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5 class="modal-title">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h6 class="mb-3">Product Information</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Product Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>SKU <span class="text-danger">*</span></label>
                                        <input type="text" name="sku" class="form-control"
                                            value="{{ old('sku') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="2">{{ old('short_description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Product Images <small>(Multiple)</small></label>
                                    <input type="file" name="images[]" class="form-control" multiple accept="image/*"
                                        id="addImages">
                                    <small class="text-muted">Allowed: JPG, PNG, JPEG (Max: 2MB each)</small>
                                    <div id="addImagePreview" class="mt-2 d-flex gap-2 flex-wrap"></div>
                                </div>

                                <h6 class="mb-3 mt-4">Additional Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Size/Volume</label>
                                        <input type="text" name="size" class="form-control"
                                            value="{{ old('size') }}" placeholder="e.g., 800 ML">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Brand</label>
                                        <input type="text" name="brand" class="form-control"
                                            value="{{ old('brand') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1" {{ old('status', 1) ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="featured" class="form-check-input"
                                                    value="1" {{ old('featured') ? 'checked' : '' }}>
                                                <label class="form-check-label">Featured Product</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Regular Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="price" class="form-control" step="0.01"
                                                value="{{ old('price') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Discount Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="discount_price" class="form-control"
                                                step="0.01" value="{{ old('discount_price') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="mb-3">
                                            <label>Stock Quantity <span class="text-danger">*</span></label>
                                            <input type="number" name="stock" class="form-control"
                                                value="{{ old('stock', 0) }}" min="0" required>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="in_stock" class="form-check-input"
                                                    value="1" {{ old('in_stock', true) ? 'checked' : '' }} checked>
                                                <label class="form-check-label">In Stock</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>






                            </div>




                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Image Preview for Add Modal
        $('#addImages').on('change', function(e) {
            const files = e.target.files;
            const preview = $('#addImagePreview');
            preview.html('');

            if (files) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.append(`
                        <div class="position-relative">
                            <img src="${e.target.result}" class="rounded" width="80" height="80" style="object-fit: cover;">
                        </div>
                    `);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });

        // Image Preview for Edit Modals
        @foreach ($products as $product)
            $('#editImages{{ $product->id }}').on('change', function(e) {
                const files = e.target.files;
                const preview = $('#editImagePreview{{ $product->id }}');
                preview.html('');

                if (files) {
                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.append(`
                        <div class="position-relative">
                            <img src="${e.target.result}" class="rounded" width="80" height="80" style="object-fit: cover;">
                        </div>
                    `);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });
        @endforeach

        // Remove Image Function
        function removeImage(productId, index) {
            Swal.fire({
                title: 'Remove Image?',
                text: "This will mark the image for removal",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`.remove-img-${index}`).val(index);
                    $(`#currentImages${productId} .position-relative:nth-child(${index + 1})`).fadeOut();
                }
            });
        }

        // Delete Confirmation
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Delete Product?',
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
    </script>
@endpush
