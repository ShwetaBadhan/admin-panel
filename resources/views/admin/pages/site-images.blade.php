{{-- resources/views/admin/pages/site-images.blade.php --}}
@extends("admin.layout.admin-master")
@section("title", "Site Images | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Site Images</li>
                </ol>
            </div>

            @if(session('success'))
                <script>Swal.fire('Success!', '{{ session('success') }}', 'success');</script>
            @endif

            <div class="row g-5">

                <!-- POPUP IMAGE CARD -->
                <div class="col-lg-6">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-window-maximize"></i> Homepage Popup Image</h5>
                        </div>
                        <div class="card-body ">

                            @if($setting->popup_image)
                                <img src="{{ asset('storage/' . $setting->popup_image) }}"
                                    class="img-fluid rounded shadow-sm mb-3" style="max-height: 280px;">

                            @else
                                <div class="bg-light border-dashed rounded d-flex align-items-center justify-content-center mb-3"
                                    style="height: 250px;">
                                    <i class="fas fa-image text-muted" style="font-size: 70px;"></i>
                                </div>
                                <p class="text-danger ">No Image Uploaded</p>
                            @endif

                            <!-- Separate Form for Popup -->
                            <form action="{{ route('site-images.update-popup') }}" method="POST"
                                enctype="multipart/form-data" class="mt-4">
                                @csrf @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label ">Change Popup Image</label>
                                    <input type="file" name="popup_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Best: 800×600px • PNG/JPG</small>
                                </div>

                                <div class="form-check text-start mb-4">
                                    <input class="form-check-input" type="checkbox" name="popup_enabled" value="1"
                                        id="popup_enabled" {{ $setting->popup_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label " for="popup_enabled">
                                        Show Popup on Homepage
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ADS BANNER CARD -->
                <div class="col-lg-6">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-ad"></i> Advertisement Banner</h5>
                        </div>
                        <div class="card-body ">

                            @if($setting->ads_image)
                                <img src="{{ asset('storage/' . $setting->ads_image) }}"
                                    class="img-fluid rounded shadow-sm mb-3" style="max-height: 280px;">

                            @else
                                <div class="bg-light border-dashed rounded d-flex align-items-center justify-content-center mb-3"
                                    style="height: 250px;">
                                    <i class="fas fa-image text-muted" style="font-size: 70px;"></i>
                                </div>
                                <p class="text-danger ">No Banner Uploaded</p>
                            @endif

                            <!-- Separate Form for Ads -->
                            <form action="{{ route('site-images.update-ads') }}" method="POST" enctype="multipart/form-data"
                                class="mt-4">
                                @csrf @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label ">Change Banner Image</label>
                                    <input type="file" name="ads_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Best: 1200×300px • Wide banner</small>
                                </div>

                                <div class="form-check text-start mb-4">
                                    <input class="form-check-input" type="checkbox" name="ads_enabled" value="1"
                                        id="ads_enabled" {{ $setting->ads_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label " for="ads_enabled">
                                        Show Banner on Website
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-warning btn-lg w-100 text-dark">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection