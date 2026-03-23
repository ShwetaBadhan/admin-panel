{{-- resources/views/admin/pages/about-section.blade.php --}}
@extends("admin.layout.admin-master")
@section("title", "Community Planning Section | Continuity Care")
@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Community Planning Section</li>
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
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i>&nbsp;Community Planning Section</h5>

                        </div>
                        <div class="card-body">


                            <form action="{{ route('community-planning.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Left Column - Image & Basic Info -->
                                    <div class="col-lg-4">
                                        <!-- Main Image -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Main Image</label>

                                            @if($CommunityPlannings->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($CommunityPlannings->image))
                                                <div class="position-relative mb-3">
                                                    <img src="{{ asset('storage/' . $CommunityPlannings->image) }}"
                                                        class="img-fluid rounded shadow-sm"
                                                        style="max-height: 300px; width: 100%; object-fit: cover;"
                                                        alt="Main Image"
                                                        onerror="this.style.display='none'; console.log('Image failed to load: {{ $CommunityPlannings->image }}')">
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
                                                        @if($CommunityPlannings->image)
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
                                                required>{{ old('main_title', $CommunityPlannings->main_title) }}</textarea>
                                            @error('main_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- sub title --}}
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Sub Title <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="sub_title"
                                                class="form-control @error('sub_title') is-invalid @enderror" rows="2"
                                                required>{{ old('sub_title', $CommunityPlannings->sub_title) }}</textarea>
                                            @error('sub_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- note --}}
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Note : <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="note"
                                                class="form-control @error('note') is-invalid @enderror" rows="2"
                                                required>{{ old('note', $CommunityPlannings->note) }}</textarea>
                                            @error('note')
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
                                                required>{{ old('description_1', $CommunityPlannings->description_1) }}</textarea>
                                            @error('description_1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Points -->
                                        <label class="form-label fw-bold mt-3">Service Points</label>

                                        <div id="points-wrapper">
                                            @if(!empty($CommunityPlannings->points) && is_array($CommunityPlannings->points))
                                                @foreach($CommunityPlannings->points as $index => $point)
                                                    <div class="input-group mb-2 point-item">
                                                        <input type="text" name="points[]" class="form-control"
                                                            value="{{ old('points.' . $index, $point) }}" placeholder="Enter point">
                                                        <button type="button" class="btn btn-outline-danger"
                                                            onclick="removePoint(this)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2 point-item">
                                                    <input type="text" name="points[]" class="form-control"
                                                        placeholder="Enter point">
                                                    <button type="button" class="btn btn-outline-danger"
                                                        onclick="removePoint(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                        <button type="button" class="btn btn-primary btn-sm mt-2" onclick="addPoint()">
                                            <i class="fas fa-plus"></i> Add Point
                                        </button>




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
        function addPoint(value = '') {
            const wrapper = document.getElementById('points-wrapper');

            const div = document.createElement('div');
            div.className = 'input-group mb-2 point-item';

            div.innerHTML = `
                            <input type="text" name="points[]" class="form-control" placeholder="Enter point" value="${value}">
                            <button type="button" class="btn btn-outline-danger" onclick="removePoint(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;

            wrapper.appendChild(div);
        }

        function removePoint(button) {
            button.closest('.point-item').remove();
        }

    </script>
@endpush