{{-- resources/views/admin/pages/about-section.blade.php --}}
@extends("admin.layout.admin-master")

@section("title", "About Us Section | Continuity Care")
@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">About Section</li>
                </ol>
            </div>

            @if(session('success'))
                <script>Swal.fire('Success!', '{{ session('success') }}', 'success');</script>
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
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> About Us Section</h5>

                        </div>
                        <div class="card-body">


                            <form action="{{ route('about-section.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Left Column - Image & Basic Info -->
                                    <div class="col-lg-4">
                                        <!-- Main Image -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Main Image</label>

                                            @if($AboutCares->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($AboutCares->image))
                                                <div class="position-relative mb-3">
                                                    <img src="{{ asset('storage/' . $AboutCares->image) }}"
                                                        class="img-fluid rounded shadow-sm"
                                                        style="max-height: 300px; width: 100%; object-fit: cover;"
                                                        alt="Main Image"
                                                        onerror="this.style.display='none'; console.log('Image failed to load: {{ $AboutCares->image }}')">
                                                    <small class="text-success d-block mt-1">
                                                        <i class="fas fa-check-circle"></i> Image loaded successfully
                                                    </small>
                                                </div>
                                            @else
                                                <div class="bg-light border-dashed rounded d-flex align-items-center justify-content-center mb-3"
                                                    style="height: 250px; border-style: dashed;">
                                                    <div class="text-center">
                                                        <i class="fas fa-image text-muted" style="font-size: 60px;"></i>
                                                        <p class="text-muted mt-2 mb-0">No image uploaded</p>
                                                        @if($AboutCares->image)
                                                            <p class="text-warning small mt-1 mb-0">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                Image file not found in storage
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <input type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                accept="image/*,.svg">
                                            <small class="text-muted">Recommended: 600×600px • PNG/JPG/SVG (Max 2MB)</small>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Feature 1 -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <label class="form-label fw-bold">Feature 1 Title <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="feature_1_title"
                                                    class="form-control @error('feature_1_title') is-invalid @enderror"
                                                    value="{{ old('feature_1_title', $AboutCares->feature_1_title) }}"
                                                    required>
                                                @error('feature_1_title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-bold">Icon 1</label>
                                                @if($AboutCares->icon_1 && \Illuminate\Support\Facades\Storage::disk('public')->exists($AboutCares->icon_1))
                                                    <div class="position-relative mb-2">
                                                        <img src="{{ asset('storage/' . $AboutCares->icon_1) }}"
                                                            class="img-fluid rounded shadow-sm"
                                                            style="max-height: 60px; max-width: 60px;" alt="Icon 1"
                                                            onerror="this.style.display='none'">
                                                    </div>
                                                @endif
                                                <input type="file" name="icon_1"
                                                    class="form-control @error('icon_1') is-invalid @enderror"
                                                    accept="image/*,.svg">
                                                <small class="text-muted">Recommended: 60x60px</small>
                                                @error('icon_1')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Right Column - Content (Keep existing content) -->
                                    <div class="col-lg-8">
                                        <!-- Sub Title -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Sub Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="sub_title"
                                                class="form-control @error('sub_title') is-invalid @enderror"
                                                value="{{ old('sub_title', $AboutCares->sub_title) }}" required>
                                            @error('sub_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Main Title -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Main Title <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="main_title"
                                                class="form-control @error('main_title') is-invalid @enderror" rows="2"
                                                required>{{ old('main_title', $AboutCares->main_title) }}</textarea>
                                            @error('main_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Description 1 -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Primary Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="description_1"
                                                class="form-control   @error('description_1') is-invalid @enderror"
                                                rows="4"
                                                required>{{ old('description_1', $AboutCares->description_1) }}</textarea>
                                            @error('description_1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Description 2 -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Secondary Description</label>
                                            <textarea name="description_2"
                                                class="form-control   @error('description_2') is-invalid @enderror"
                                                rows="3">{{ old('description_2', $AboutCares->description_2) }}</textarea>
                                            @error('description_2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>



                                    </div>
                                </div>
                                <hr>
                                <div class="text-start mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {

            $('.summernote').summernote({
                height: 200,
                placeholder: 'Write the message here...'
            });
        });
    </script>
@endpush