@extends("admin.layout.admin-master")
@section("title", "CC Point Settings | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Settings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">CC Point Settings</a></li>
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
                    
                    <div class="card">
                        <div class="card-header bg-theme-light">
                            <h4 class="card-title">CC Point Conversion Configuration</h4>
                        </div>
                        <div class="card-body">
                            
                            <!-- Update Form -->
                            <form action="{{ route('cc-settings.update', $setting->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-lg-6 mb-4">
                                        <label for="cc_rate" class="form-label fw-bold">Conversion Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" name="value" id="cc_rate"
                                                   class="form-control @error('value') is-invalid @enderror"
                                                   value="{{ old('value', $setting->value) }}" required>
                                        </div>
                                        @error('value')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted mt-2 d-block">1 CC = ₹{{ number_format($setting->value, 2) }}</small>
                                    </div>

                                    <div class="col-lg-6 mb-4">
                                        <label for="cc_is_active" class="form-label fw-bold">Status</label>
                                        <select name="is_active" id="cc_is_active" class="form-control @error('is_active') is-invalid @enderror">
                                            <option value="1" {{ old('is_active', $setting->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $setting->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('is_active')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted mt-2 d-block">Enable/Disable CC conversion</small>
                                    </div>
                                </div>

                                <div class="alert alert-info mb-4">
                                    <i class="fa fa-info-circle me-1"></i> When active, all CC point calculations will use the rate defined above.
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                  
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fa fa-save me-1"></i> Save Changes
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