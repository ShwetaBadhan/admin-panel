@extends("admin.layout.admin-master")

@section("title", "Privacy Policy Content | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Privacy Policy Content</li>
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
                        <form action="{{ route('admin-privacy-policy.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-header d-flex justify-content-between align-items-center bg-theme-light">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i>&nbsp; Privacy Policy Content</h5>

                                {{-- <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{
                                        old('is_active', $PrivacyPolicyContent->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label">Enable Section</label>
                                </div> --}}
                            </div>
                            <div class="card-body">




                                <div class="row">


                                    <!-- Right Column - Content (Keep existing content) -->
                                    <div class="col-lg-12">
                                        <!-- Sub Title -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Sub Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="sub_title"
                                                class="form-control @error('sub_title') is-invalid @enderror"
                                                value="{{ old('sub_title', $PrivacyPolicyContent->sub_title) }}" required>
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
                                                required>{{ old('main_title', $PrivacyPolicyContent->main_title) }}</textarea>
                                            @error('main_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Description 1 -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Primary Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="description"
                                                class="form-control summernote">{!! old('description', $PrivacyPolicyContent->description)  !!}</textarea>

                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


                                    </div>
                                </div>

                                <div class="text-start  mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>Update
                                    </button>
                                </div>

                            </div>
                        </form>
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