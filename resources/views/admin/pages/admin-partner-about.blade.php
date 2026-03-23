@extends("admin.layout.admin-master")

@section("title", "Partner About Section | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Partner About Section</li>
                </ol>
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

            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i>Partner About Section</h5>
                            {{-- <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{
                                    old('is_active', $PartnersAbout->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label">Enable Section</label>
                            </div> --}}
                        </div>
                        <div class="card-body">


                            <form action="{{ route('admin-partner-about.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Left Column - Image & Basic Info -->
                                    <div class="col-lg-4">
                                        <!-- Main Image -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Main Image</label>

                                            @if($PartnersAbout->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($PartnersAbout->image))
                                                <div class="position-relative mb-3">
                                                    <img src="{{ asset('storage/' . $PartnersAbout->image) }}"
                                                        class="img-fluid rounded shadow-sm"
                                                        style="max-height: 300px; width: 100%; object-fit: cover;"
                                                        alt="Main Image"
                                                        onerror="this.style.display='none'; console.log('Image failed to load: {{ $PartnersAbout->image }}')">
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
                                                        @if($PartnersAbout->image)
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
                                        <!-- Sub Title -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Sub Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="sub_title"
                                                class="form-control @error('sub_title') is-invalid @enderror"
                                                value="{{ old('sub_title', $PartnersAbout->sub_title) }}" required>
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
                                                required>{{ old('main_title', $PartnersAbout->main_title) }}</textarea>
                                            @error('main_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Description 1 -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Primary Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="description"
                                                class="form-control @error('description') is-invalid @enderror" rows="4"
                                                required>{{ old('description', $PartnersAbout->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


                                    </div>
                                </div>

                                <div class="text-end mt-4">
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

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.summernote').summernote({
                    height: 200

                });

                // Ensure checkbox sends correct value
                $('#statusSwitch').on('change', function () {
                    $(this).val(this.checked ? 1 : 0);
                }).trigger('change');
            });
        </script>
    @endpush
@endsection