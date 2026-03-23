@extends('admin.layout.admin-master')
@section('title', 'Job Careers | Continuity Care')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Job Careers</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <div class="input-group search-area w-25">
                    {{-- <input type="text" class="form-control" placeholder="Search jobs..." id="searchInput">
                    <span class="input-group-text"><i class="flaticon-381-search-2"></i></span> --}}
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addJobModal">
                    + Add Job 
                </button>
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

            <!-- Jobs Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped bg-theme" id="jobsTable">
                                    <thead class="">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            <th>Salary</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jobs as $index => $job)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ Str::limit($job->title, 40) }}</strong>
                                                    @if($job->is_featured)
                                                        <span class="badge bg-danger ms-2">Urgent</span>
                                                    @endif
                                                </td>
                                                <td><span class="badge bg-info">{{ $job->type }}</span></td>
                                                <td>{{ $job->location }}</td>
                                                <td>{{ $job->salary_range ?: '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge light badge-{{ $job->is_active ? 'success' : 'danger' }} px-3 py-2">
                                                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info light me-1" data-bs-toggle="modal"
                                                        data-bs-target="#viewModal{{ $job->id }}" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning light me-1" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $job->id }}" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger light delete-job"
                                                        data-id="{{ $job->id }}" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    No job openings found.
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
        </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addJobModal" tabindex="-1">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('jobcareer.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5 class="modal-title">Add New Job </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Enter Job Title" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Type <span class="text-danger">*</span></label>
                                <input type="text" name="type" placeholder="Enter Job Type" class="form-control"
                                    placeholder="Full Time, Contract, etc." required>
                            </div>
                            <div class="col-md-6">
                                <label>Location <span class="text-danger">*</span></label>
                                <input type="text" placeholder="enter location" name="location" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label>Salary Range</label>
                                <input type="text" name="salary_range" placeholder="Enter salary range" class="form-control"
                                    placeholder="e.g. ₹30,000 - ₹50,000">
                            </div>
                            <div class="col-md-6">
                                <label>Experience <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Enter experience needed" name="experience"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Qualification <span class="text-danger">*</span></label>
                                <input type="text" placeholder="enter qualification needed" name="qualification"
                                    class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label>Description</label>
                                <textarea name="description" placeholder="Enter additional description"
                                    class="form-control summernote" rows="5"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-3">
                                    <input type="checkbox" name="is_featured" value="1" class="form-check-input"
                                        id="add_featured">
                                    <label class="form-check-label text-danger fw-bold" for="add_featured">Urgent
                                        Hiring</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-3">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                        id="add_active" checked>
                                    <label class="form-check-label text-success fw-bold" for="add_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Job</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- VIEW MODAL FOR EACH JOB --}}
    @foreach($jobs as $job)
        <div class="modal fade" id="viewModal{{ $job->id }}" tabindex="-1">
            <div class="modal-dialog custom-modal">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h5 class="modal-title">Job Opening Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table table-bordered table-striped ">
                            <tr>
                                <th width="25%">Job Title</th>
                                <td colspan="3"><strong>{{ $job->title }}</strong></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ $job->type }}</td>
                                <th>Location</th>
                                <td>{{ $job->location }}</td>
                            </tr>
                            <tr>
                                <th>Experience</th>
                                <td>{{ $job->experience }}</td>
                                <th>Qualification</th>
                                <td>{{ $job->qualification }}</td>
                            </tr>
                            <tr>
                                <th>Salary Range</th>
                                <td colspan="3">{{ $job->salary_range ?: '<em class="text-muted">Not specified</em>' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $job->is_active ? 'success' : 'danger' }}">
                                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <th>Featured</th>
                                <td>
                                    <span class="badge bg-{{ $job->is_featured ? 'danger' : 'secondary' }}">
                                        {{ $job->is_featured ? 'Urgent Hiring' : 'Normal' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created On</th>
                                <td colspan="3">{{ $job->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Description :</th>
                                <td colspan="3"> {!! $job->description !!}</td>
                            </tr>
                        </table>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- EDIT MODAL FOR EACH JOB --}}
    @foreach($jobs as $job)
        <div class="modal fade" id="editModal{{ $job->id }}" tabindex="-1">
            <div class="modal-dialog custom-modal">
                <form action="{{ route('jobcareer.update', $job) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-theme-light">
                            <h5 class="modal-title">Edit Job{{ $job->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="col-form-label">Job Title:</label>
                                    <input type="text" name="title" value="{{ old('title', $job->title) }}" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Job Type:</label>
                                    <input type="text" name="type" value="{{ old('type', $job->type) }}" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Job Location:</label>
                                    <input type="text" name="location" value="{{ old('location', $job->location) }}"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Salary Range:</label>
                                    <input type="text" name="salary_range" value="{{ old('salary_range', $job->salary_range) }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Job Experience:</label>
                                    <input type="text" name="experience" value="{{ old('experience', $job->experience) }}"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Job Qualification:</label>
                                    <input type="text" name="qualification"
                                        value="{{ old('qualification', $job->qualification) }}" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="col-form-label">Job Description:</label>
                                    <textarea name="description"
                                        class="summernote">{!! old('description', $job->description) !!}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-3">
                                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" {{ old('is_featured', $job->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger fw-bold">Urgent Hiring</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-3">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $job->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label text-success fw-bold">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Update Job</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @push('scripts')
        <script>
            // Live Search
            document.getElementById('searchInput')?.addEventListener('keyup', function () {
                let filter = this.value.toLowerCase();
                document.querySelectorAll('#jobsTable tbody tr').forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });

            // SweetAlert2 Delete Confirmation
            $(document).on('click', '.delete-job', function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This job opening will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/jobcareer/${id}`;

                        const token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = '{{ csrf_token() }}';
                        form.appendChild(token);

                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        form.appendChild(method);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            // Summernote Init
            $(document).ready(function () {
                $('.summernote').summernote({
                    height: 200,

                });
            });
        </script>
    @endpush
@endsection