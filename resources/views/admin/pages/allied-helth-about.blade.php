{{-- resources/views/admin/pages/about-section.blade.php --}}
@extends("admin.layout.admin-master")
@section("title", "Allied Health About Section | Continuity Care")
@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Allied Health About Section</li>
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
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i>&nbsp;Allied Health About Section</h5>

                        </div>
                        <div class="card-body">


                            <form action="{{ route('allied-health-about.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Left Column - Image & Basic Info -->
                                    <div class="col-lg-4">
                                        <!-- Main Image -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Main Image</label>

                                            @if($AlliedHealthAbouts->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($AlliedHealthAbouts->image))
                                                <div class="position-relative mb-3">
                                                    <img src="{{ asset('storage/' . $AlliedHealthAbouts->image) }}"
                                                        class="img-fluid rounded shadow-sm"
                                                        style="max-height: 300px; width: 100%; object-fit: cover;"
                                                        alt="Main Image"
                                                        onerror="this.style.display='none'; console.log('Image failed to load: {{ $AlliedHealthAbouts->image }}')">
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
                                                        @if($AlliedHealthAbouts->image)
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


                                    </div>

                                    <!-- Right Column - Content (Keep existing content) -->
                                    <div class="col-lg-8">


                                        <!-- Main Title -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Main Title <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="main_title"
                                                class="form-control @error('main_title') is-invalid @enderror" rows="2"
                                                required>{{ old('main_title', $AlliedHealthAbouts->main_title) }}</textarea>
                                            @error('main_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Description 1 -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Primary Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="description_1"
                                                class="form-control summernote  @error('description_1') is-invalid @enderror"
                                                rows="4"
                                                required>{{ old('description_1', $AlliedHealthAbouts->description_1) }}</textarea>
                                            @error('description_1')
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