@extends("admin.layout.admin-master")
@section("title", "Website Settings | Continuity Care")

@section("content")
<div class="content-body">
    <div class="container-fluid">

        <!-- Breadcrumb -->
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Website Settings</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">General</a></li>
            </ol>
        </div>

        <!-- SweetAlert2 Success Message -->
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ addslashes(session("success")) }}',
                    confirmButtonColor: '#3085d6',
                    timer: 4000,
                    timerProgressBar: true,
                });
            </script>
        @endif

        <div class="row">
            @include("admin.components.setting-sidebar")

            <div class="col-lg-9 ps-0">

                <!-- FORM 1: General Settings -->
                <form action="{{ route('website-setting.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-4">
                        <div class="card-header bg-theme-light">
                            <h4 class="card-title">General Settings</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label>Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control"
                                           value="{{ old('company_name', $setting->company_name) }}" required>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email', $setting->email) }}" required>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>Location <span class="text-danger">*</span></label>
                                    <input type="text" name="location" class="form-control"
                                           value="{{ old('location', $setting->location) }}" required>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>Phone Number 1 <span class="text-danger">*</span></label>
                                    <input type="text" name="phone1" class="form-control"
                                           value="{{ old('phone1', $setting->phone1) }}" required>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>Phone Number 2</label>
                                    <input type="text" name="phone2" class="form-control"
                                           value="{{ old('phone2', $setting->phone2) }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>About Company</label>
                                    <textarea name="about" id="aboutCompany" class="form-control summernote">
                                        {{ old('about', $setting->about) }}
                                    </textarea>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-5">
                                    Save 
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- FORM 2: Social Links -->
                <form action="{{ route('website-setting.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header bg-theme-light">
                            <h4 class="card-title">Social Media Links</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label>Facebook</label>
                                    <input type="url" name="facebook" class="form-control" placeholder="https://facebook.com/yourpage"
                                           value="{{ old('facebook', $setting->social_links['facebook'] ?? '') }}">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>Instagram</label>
                                    <input type="url" name="instagram" class="form-control" placeholder="https://instagram.com/yourpage"
                                           value="{{ old('instagram', $setting->social_links['instagram'] ?? '') }}">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>LinkedIn</label>
                                    <input type="url" name="linkedin" class="form-control" placeholder="https://linkedin.com/company/yourpage"
                                           value="{{ old('linkedin', $setting->social_links['linkedin'] ?? '') }}">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>Twitter (X)</label>
                                    <input type="url" name="twitter" class="form-control" placeholder="https://twitter.com/yourhandle"
                                           value="{{ old('twitter', $setting->social_links['twitter'] ?? '') }}">
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


<script>
    $(document).ready(function() {
        $('#aboutCompany').summernote({
            height: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endsection