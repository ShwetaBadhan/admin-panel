@extends('admin.layout.admin-master')

@section('title', 'Services Section | Continuity Care')
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Services Section</li>
                </ol>
            </div>

            @if (session('success'))
                <script>
                    Swal.fire('Success!', '{{ session('success') }}', 'success');
                </script>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-theme-light">
                            <h5 class="mb-0"><i class="fas fa-tractor"></i> Services Section Configuration</h5>
                            <span class="badge bg-primary">Dynamic Fields Enabled</span>
                        </div>
                        <div class="card-body">

                            {{-- ✅ Updated Form Action --}}
                            <form action="{{ url('/services/' . $section->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')


                                <div class="row">
                                    <!-- LEFT COLUMN: Images & Dynamic Pointers -->
                                    <div class="col-lg-4 border-end">

                                        <!-- Main Image -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Main Image (Right Side Circle)</label>
                                            @if ($section->image && Storage::disk('public')->exists($section->image))
                                                <div class="position-relative mb-2 text-center">
                                                    <img src="{{ asset('storage/' . $section->image) }}"
                                                        class="img-fluid rounded-circle shadow-sm"
                                                        style="max-height: 200px; width: 200px; object-fit: cover;"
                                                        alt="Main Image">
                                                </div>
                                            @else
                                                <div class="bg-light border-dashed rounded d-flex align-items-center justify-content-center mb-2"
                                                    style="height: 200px;">
                                                    <i class="fas fa-image text-muted fa-3x"></i>
                                                </div>
                                            @endif
                                            <input type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                            <small class="text-muted">Recommended: 500x500px</small>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <hr>

                                        <!-- Center Icon -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Center Icon (Yellow Circle)</label>
                                            @if ($section->icon && Storage::disk('public')->exists($section->icon))
                                                <div class="mb-2 text-center">
                                                    <img src="{{ asset('storage/' . $section->icon) }}" class="img-fluid"
                                                        style="max-height: 50px;" alt="Icon">
                                                </div>
                                            @endif
                                            <input type="file" name="icon"
                                                class="form-control @error('icon') is-invalid @enderror"
                                                accept="image/*,.svg">
                                            <small class="text-muted">Recommended: 60x60px SVG/PNG</small>
                                            @error('icon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <hr>

                                        <!-- DYNAMIC POINTERS (The Array) -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label fw-bold mb-0">Left Side Pointers</label>
                                                <button type="button" class="btn btn-sm btn-success" id="addPointerBtn">
                                                    <i class="fas fa-plus"></i> Add Pointer
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mb-3">Add as many items as you need for the
                                                left list.</small>

                                            <div id="pointersContainer">
                                                <!-- Items will be injected here by JS -->
                                            </div>
                                        </div>

                                    </div>

                                    <!-- RIGHT COLUMN: Text Content -->
                                    <div class="col-lg-8">

                                        <!-- Sub Title -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Sub Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="subtitle"
                                                class="form-control @error('subtitle') is-invalid @enderror"
                                                value="{{ old('subtitle', $section->subtitle) }}" required>
                                            @error('subtitle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Main Heading -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Main Heading <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="main_heading"
                                                class="form-control @error('main_heading') is-invalid @enderror"
                                                value="{{ old('main_heading', $section->main_heading) }}" required>
                                            @error('main_heading')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <hr class="my-4">

                                        <!-- Active Item Details -->
                                        <h6 class="text-primary mb-3"><i class="fas fa-pen"></i> Active Item Details</h6>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Active Item Title</label>
                                                <input type="text" name="active_item_title"
                                                    class="form-control @error('active_item_title') is-invalid @enderror"
                                                    value="{{ old('active_item_title', $section->active_item_title) }}"
                                                    required>
                                                <small class="text-muted">This should match one of the pointers on the
                                                    left.</small>
                                                @error('active_item_title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Read More Link</label>
                                                <input type="text" name="read_more_link"
                                                    class="form-control @error('read_more_link') is-invalid @enderror"
                                                    value="{{ old('read_more_link', $section->read_more_link) }}">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="active_item_description"
                                                class="form-control summernote @error('active_item_description') is-invalid @enderror" rows="5" required>{{ old('active_item_description', $section->active_item_description) }}</textarea>
                                            @error('active_item_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <hr>
                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for Dynamic Pointer Row (Hidden) -->
    <template id="pointerTemplate">
        <div class="pointer-item input-group mb-2">
            <input type="text" name="service_items[__index__][title]" class="form-control"
                placeholder="Title (e.g. Organic Product)" required>
            <input type="text" name="service_items[__index__][link]" class="form-control"
                placeholder="Link (Optional)">
            <button type="button" class="btn btn-danger remove-pointer" title="Remove">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 200,
                placeholder: 'Write description here...'
            });

            // --- DYNAMIC POINTERS LOGIC ---

            const container = $('#pointersContainer');
            const template = $('#pointerTemplate').html();
            let index = 0;

            // Load existing data from Blade (JSON converted to JS Object)
            const existingItems = @json(old('service_items', $section->service_items ?? []));

            function renderPointer(title = '', link = '') {
                let row = template.replace(/__index__/g, index);
                let $row = $(row);

                // Set values if editing
                $row.find('input[name*="[title]"]').val(title);
                $row.find('input[name*="[link]"]').val(link);

                container.append($row);
                index++;
            }

            // Initial Render
            if (existingItems.length > 0) {
                existingItems.forEach(item => {
                    renderPointer(item.title, item.link);
                });
            } else {
                // Add 4 default empty rows if nothing exists
                for (let i = 0; i < 4; i++) renderPointer();
            }

            // Add New Row
            $('#addPointerBtn').click(function() {
                renderPointer();
            });

            // Remove Row
            $(document).on('click', '.remove-pointer', function() {
                if ($('.pointer-item').length > 1) {
                    $(this).closest('.pointer-item').remove();
                } else {
                    Swal.fire('Info', 'You need at least one pointer item.', 'info');
                }
            });
        });
    </script>
@endpush
