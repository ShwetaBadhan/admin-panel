@extends("admin.layout.admin-master")
@section("title", "Care Coordination FAQs | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Care Coordination FAQs</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-4 align-items-center justify-content-between">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                        + Add FAQ
                    </button>
                </div>
            </div>

            @if(session('success'))
                <script>
                    Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session("success") }}', timer: 4000 });
                </script>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="faqTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faqs as $faq)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ Str::limit($faq->question, 60) }}</strong></td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $faq->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($faq->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $faq->created_at->format('d M Y') }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $faq->id }}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $faq->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form action="{{ route('care-coordination-faqs.destroy', $faq) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger light delete-btn">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            No FAQs found. Click "+ Add FAQ" to create one.
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
        <div class="modal-dialog custom-modal">
            <form action="{{ route('care-coordination-faqs.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5>Add New FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Question <span class="text-danger">*</span></label>
                            <input type="text" name="question" class="form-control" placeholder="Write Question Here"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Answer <span class="text-danger">*</span></label>
                            <textarea name="answer" class="summernote" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
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
    @foreach($faqs as $faq)
        <!-- View Modal -->
        <div class="modal fade" id="view{{ $faq->id }}" tabindex="-1">
            <div class="modal-dialog modal-centered custom-modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">FAQ Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Table Details -->
                    <table class="table table-bordered table-striped mb-0">

                        <tr>
                            <th>Question :</th>
                            <td colspan="3">{{ $faq->question }}</td>

                        </tr>
                        <tr>
                            <th>Answer : </th>
                            <td colspan="3"> {!! $faq->answer !!}</td>
                        </tr>
                        <tr>
                            <th>Status :</th>
                            <td colspan="3">
                                <span class="badge bg-{{ $faq->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($faq->status) }}
                                </span>
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
        <div class="modal fade" id="edit{{ $faq->id }}">
            <div class="modal-dialog custom-modal">
                <form action="{{ route('care-coordination-faqs.update', $faq) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-theme-light">
                            <h5>Edit FAQ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Question</label>
                                <input type="text" name="question" value="{{ $faq->question }}" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Answer</label>
                                <textarea name="answer" class="summernote">{!! $faq->answer !!}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" {{ $faq->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $faq->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">

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
        $(function () {
            $('.summernote').summernote({
                height: 200,
                placeholder: 'Write the answer here...'
            });

            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $("#faqTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('.delete-btn').click(function (e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete FAQ?',
                    text: "This FAQ will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush