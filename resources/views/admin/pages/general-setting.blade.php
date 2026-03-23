@extends("admin.layout.admin-master")
@section("title", "General Settings | Continuity Care")

@section("content")
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">General Settings</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Captcha</a></li>
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
            @include("admin.components.setting-sidebar")

            <div class="col-lg-9 ps-0">

                <!-- CLOUDFLARE FORM -->
<form action="{{ route('general-setting.update') }}" method="POST">
    @csrf @method('PUT')
    <input type="hidden" name="captcha_type" value="cloudflare">

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-theme-light">
            <h4 class="card-title mb-0">Cloudflare Turnstile</h4>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="cloudflare_status"
                       {{ $cloudflare->cloudflare_active ? 'checked' : '' }}>
                <label class="form-check-label text-success fw-bold">Active</label>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label>Site Key <span class="text-danger">*</span></label>
                    <input type="text" name="cloudflare_site_key" class="form-control" placeholder="Enter site key"
                           value="{{ old('cloudflare_site_key', $cloudflare->site_key) }}" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Secret Key <span class="text-danger">*</span></label>
                    <input type="text" name="cloudflare_secret_key" class="form-control" placeholder="Enter secret key"
                           value="{{ old('cloudflare_secret_key', $cloudflare->secret_key) }}" required>
                </div>
                <div class="col-lg-12 mb-3">
                    <label>Domain (optional)</label>
                    <input type="text" name="cloudflare_domain" class="form-control"
                           value="{{ old('cloudflare_domain', $cloudflare->domain) }}" placeholder="example.com">
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-success px-5">
                    Save 
                </button>
            </div>
        </div>
    </div>
</form>

<!-- GOOGLE FORM -->
<form action="{{ route('general-setting.update') }}" method="POST">
    @csrf @method('PUT')
    <input type="hidden" name="captcha_type" value="google">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-theme-light">
            <h4 class="card-title mb-0">Google reCAPTCHA </h4>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="google_status"
                       {{ $google->google_active ? 'checked' : '' }}>
                <label class="form-check-label text-success fw-bold">Active</label>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label>Site Key <span class="text-danger">*</span></label>
                    <input type="text" name="google_site_key" class="form-control" placeholder="Enter site key"
                           value="{{ old('google_site_key', $google->site_key) }}" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Secret Key <span class="text-danger">*</span></label>
                    <input type="text" name="google_secret_key" class="form-control" placeholder="Enter secret key"
                           value="{{ old('google_secret_key', $google->secret_key) }}" required>
                </div>
                <div class="col-lg-12 mb-3">
                    <label>Domain (optional)</label>
                    <input type="text" name="google_domain" class="form-control"
                           value="{{ old('google_domain', $google->domain) }}" placeholder="example.com">
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-success px-5">
                    Save 
                </button>
            </div>
        </div>
    </div>
</form>
            </div>
        </div>
    </div>
</div>

@endsection