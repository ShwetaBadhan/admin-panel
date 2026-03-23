@extends("admin.layout.admin-master")
@section("title", "Aged Services Section | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Aged Services Section</li>
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
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center bg-theme-light">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> &nbsp;Aged Services Section</h5>

                        </div>

                        <div class="card-body">
                            <form action="{{ route('aged-service.update', $section) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <ul class="nav nav-tabs mb-4" id="contentTabs">
                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                            href="#text-content">Text Content</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                            href="#feature-cards">Feature Cards</a></li>
                                </ul>

                                <div class="tab-content border border-top-0 rounded-bottom p-4 ">
                                    <!-- Text Content -->
                                    <div class="tab-pane fade show active" id="text-content">
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold">Main Title *</label>
                                                <input type="text" name="main_title" class="form-control"
                                                value="{{ old('main_title', $section->main_title) }}" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold">Sub Title *</label>
                                                <input type="text" name="sub_title" class="form-control"
                                                    value="{{ old('sub_title', $section->sub_title) }}" required>
                                            </div>
                                           
                                          

                                        </div>
                                    </div>


                                    <!-- Feature Cards -->
                                    <div class="tab-pane fade" id="feature-cards">
                                        <div class="row g-4">
                                            @for($i = 1; $i <= 3; $i++)
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="card h-100 shadow-sm border-0">
                                                        <div class="card-body ">
                                                             <label class="form-label fw-bold">Service {{ $i }}*</label>
                                                            <input type="text" name="small_card_{{ $i }}_title"
                                                                class="form-control mb-3"
                                                                value="{{ old("small_card_{$i}_title", $section->{"small_card_{$i}_title"}) }}"
                                                                placeholder="Card Title" required>
                                                            <input type="text" name="small_card_{{ $i }}_content"
                                                                class="form-control mb-3"
                                                                value="{{ old("small_card_{$i}_content", $section->{"small_card_{$i}_content"}) }}"
                                                                placeholder="Card Title" required>
                                                          
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <div class="text-start mt-5">
                                    <button type="submit" class="btn btn-success btn-lg px-6">
                                       <i class="fas fa-save me-2"></i> Update
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