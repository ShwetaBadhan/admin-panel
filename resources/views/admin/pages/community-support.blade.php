@extends("admin.layout.admin-master")
@section("title", "Community Support Card | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Community Support Card</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <div class="input-group search-area w-25">
                    {{-- <input type="text" id="searchInput" class="form-control" placeholder="Search testimonials...">
                    <span class="input-group-text"><i class="flaticon-381-search-2"></i></span> --}}
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                    + Add Support
                </button>
            </div>

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

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="testimonialTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>title</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($CommunitySupportCard as $card)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($card->image)
                                                <img src="{{ asset('storage/' . $card->image) }}" width="50" class="rounded-circle">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width:50px;height:50px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td><strong>{{ $card->main_title }}</strong></td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $card->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($card->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $card->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $card->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('community-support.destroy', $card) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger light delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            No Section Card found. Click "+ Add Support" to create one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog custom-modal bg-theme-light">
            <form action="{{ route('community-support.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5>Add New Support Card</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label>Main Title <span class="text-danger">*</span></label>
                                <input type="text" name="main_title" class="form-control" placeholder="Title" required>
                            </div>

                            <div class="col-md-6">
                                <label>Icon/Image</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- Dynamic Points -->
                            <div class="col-12">
                                <label class="fw-bold mt-3">Support Points</label>

                                <div id="points-wrapper">
                                    <div class="input-group mb-2 point-item">
                                        <input type="text" name="points[]" class="form-control" placeholder="Enter point">
                                        <button type="button" class="btn btn-outline-danger" onclick="removePoint(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary btn-sm mt-2" onclick="addPoint()">
                                    <i class="fas fa-plus"></i> Add Point
                                </button>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View & Edit Modals -->
    @foreach($CommunitySupportCard as $card)
        <!-- View Modal -->
        <div class="modal fade" id="view{{ $card->id }}" tabindex="-1">
            <div class="modal-dialog custom-modal modal-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Section Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Table Layout -->
                    <table class="table table-bordered table-striped mb-0">

                        <tr>
                            <th>Title :</th>
                            <td><strong>{{ $card->main_title }}</strong></td>

                            <th>Status :</th>
                            <td>
                                <span class="badge bg-{{ $card->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($card->status) }}
                                </span>
                            </td>
                        </tr>



                        <tr>
                            <th>Image :</th>
                            <td>
                                @if($card->image)
                                    <img src="{{ asset('storage/' . $card->image) }}" width="120" class=" mb-2">
                                @else
                                    <em class="text-muted">No image uploaded</em>
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <th>Points :</th>
                            <td colspan="3">
                                @if(!empty($card->points))
                                    <ul class="mb-0">
                                        @foreach($card->points as $point)
                                            <li>{{ $point }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <em>No points added</em>
                                @endif
                            </td>
                        </tr>


                    </table>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Edit Modal -->
        <div class="modal fade" id="edit{{ $card->id }}">
            <div class="modal-dialog custom-modal">
                <form action="{{ route('community-support.update', $card) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-theme-light">
                            <h5>Edit section</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Title</label>
                                    <input type="text" name="main_title" value="{{ $card->main_title }}" class="form-control"
                                        required>
                                </div>


                                <div class="col-md-6">
                                    <label>Change Photo</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    @if($card->image)
                                        <img src="{{ asset('storage/' . $card->image) }}" width="80" class="mt-2 rounded-circle">
                                    @endif
                                </div>


                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $card->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $card->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="fw-bold">Support Points</label>

                                    <div id="edit-points-{{ $card->id }}">
                                        @foreach($card->points ?? [] as $point)
                                            <div class="input-group mb-2 point-item">
                                                <input type="text" name="points[]" value="{{ $point }}" class="form-control">
                                                <button type="button" class="btn btn-outline-danger" onclick="removePoint(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>


                                    <button type="button" class="btn btn-primary btn-sm mt-2"
                                        onclick="addPoint('edit-points-{{ $card->id }}')">
                                        <i class="fas fa-plus"></i> Add Point
                                    </button>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        function addPoint(wrapperId = 'points-wrapper') {
            const wrapper = document.getElementById(wrapperId);
            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2', 'point-item');
            div.innerHTML = `
                    <input type="text" name="points[]" class="form-control" placeholder="Enter point">
                    <button type="button" class="btn btn-outline-danger" onclick="removePoint(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            wrapper.appendChild(div);
        }

        function removePoint(btn) {
            btn.closest('.point-item').remove();
        }
    </script>

@endpush