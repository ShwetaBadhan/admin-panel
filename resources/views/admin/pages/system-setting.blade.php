@extends("admin.layout.admin-master")
@section("title", "System Settings | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">System Settings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Appearance</a></li>
                </ol>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 4000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            <div class="row">
                @include("admin.components.setting-sidebar")
                <div class="col-lg-9 ps-0">
                    <form action="{{ route('system-setting.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-header bg-theme-light">
                                <h4 class="card-title">Logo & Appearance Settings</h4>
                            </div>
                            <div class="card-body">

                                <!-- Black Logo -->
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label>Black Logo (Main Logo)</label>
                                        <input type="file" name="black_logo" class="form-control" accept="image/*">
                                        @if($setting->black_logo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $setting->black_logo) }}" width="150"
                                                    class="img-thumbnail">
                                                <small class="text-muted">Current Black Logo</small>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- White Logo -->
                                    <div class="col-lg-6">
                                        <label>White Logo (For Dark Background)</label>
                                        <input type="file" name="white_logo" class="form-control" accept="image/*">
                                        @if($setting->white_logo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $setting->white_logo) }}" width="150"
                                                    class="img-thumbnail">
                                                <small class="text-muted">Current White Logo</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Backend & Favicon -->
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label>Backend Panel Logo (Login Page)</label>
                                        <input type="file" name="backend_logo" class="form-control" accept="image/*">
                                        @if($setting->backend_logo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $setting->backend_logo) }}" width="120"
                                                    class="img-thumbnail">
                                                <small class="text-muted">Current Backend Logo</small>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Favicon</label>
                                        <input type="file" name="favicon" class="form-control" accept=".png,.ico,.jpg">
                                        @if($setting->favicon)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $setting->favicon) }}" width="64" height="64"
                                                    class="img-thumbnail">
                                                <small class="text-muted">Current Favicon</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Helpdesk Number -->
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label>Helpdesk / Support Number</label>
                                        <input type="text" name="helpdesk_number" class="form-control"
                                            value="{{ old('helpdesk_number', $setting->helpdesk_number) }}"
                                            placeholder="+91 98765 43210">
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
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