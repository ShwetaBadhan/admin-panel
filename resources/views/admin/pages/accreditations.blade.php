@extends("admin.layout.admin-master")
@section("title", "Accreditations Section | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Accreditations Section</li>
                </ol>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Accreditations Section <badge class="text-danger" style="font-size: 10px;">
                            Note : the icon size should be upto 2mb</badge>
                    </h4>

                </div>
                <div class="card-body">

                    <!-- Success Message -->
                    @if(session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: '{{ session("success") }}',
                                timer: 4000,
                                timerProgressBar: true
                            });
                        </script>
                    @endif

                    <!-- Fixed Form: Use PUT method -->
                    <form action="{{ route('accreditations.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Accreditation 1 -->
                            <div class="col-lg-6">
                                <label class="form-label">Title 1</label>
                                <input type="text" name="title1" placeholder="Enter title"
                                    value="{{ old('title1', $section->title1) }}" class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Icon 1 (Image/SVG)</label>
                                <input type="file" name="icon1" class="form-control" accept="image/*,.svg">
                                @if($section->icon1)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($section->icon1) }}" alt="Icon 1" width="80"
                                            class="img-thumbnail">
                                        <small class="text-muted d-block">Current Icon</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Accreditation 2 -->
                            <div class="col-lg-6">
                                <label class="form-label">Title 2</label>
                                <input type="text" name="title2" placeholder="Enter title"
                                    value="{{ old('title2', $section->title2) }}" class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Icon 2 (Image/SVG)</label>
                                <input type="file" name="icon2" class="form-control" accept="image/*,.svg">
                                @if($section->icon2)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($section->icon2) }}" alt="Icon 2" width="80"
                                            class="img-thumbnail">
                                        <small class="text-muted d-block">Current Icon</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Accreditation 3 -->
                            <div class="col-lg-6">
                                <label class="form-label">Title 3</label>
                                <input type="text" name="title3" placeholder="Enter title"
                                    value="{{ old('title3', $section->title3) }}" class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Icon 3 (Image/SVG)</label>
                                <input type="file" name="icon3" class="form-control" accept="image/*,.svg">
                                @if($section->icon3)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($section->icon3) }}" alt="Icon 3" width="80"
                                            class="img-thumbnail">
                                        <small class="text-muted d-block">Current Icon</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Accreditation 4 -->
                            <div class="col-lg-6">
                                <label class="form-label">Title 4</label>
                                <input type="text" name="title4" placeholder="Enter title"
                                    value="{{ old('title4', $section->title4) }}" class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Icon 4 (Image/SVG)</label>
                                <input type="file" name="icon4" class="form-control" accept="image/*,.svg">
                                @if($section->icon4)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($section->icon4) }}" alt="Icon 4" width="80"
                                            class="img-thumbnail">
                                        <small class="text-muted d-block">Current Icon</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection