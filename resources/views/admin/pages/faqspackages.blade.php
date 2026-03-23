{{-- resources/views/admin/pages/faqspackages.blade.php --}}
@extends("admin.layout.admin-master")

@section("title", "Package FAQs | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">

            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Package FAQs</li>
                </ol>
            </div>

            <!-- Header -->
            <div class="form-head d-flex mb-3 mb-md-4 align-items-center justify-content-between">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="testSearch" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                    + Add FAQ
                </button>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <script>Swal.fire('Success!', '{{ session('success') }}', 'success');</script>
            @endif

            <!-- Table -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="faqTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Linked To</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="testTableBody">
                                @forelse($faqs as $faq)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ Str::limit($faq->question, 60) }}</td>
                                        <td>
                                            @if($faq->parameter)
                                                <span class="badge bg-info">Parameter: {{ $faq->parameter->title }}</span>
                                            @elseif($faq->subparameter)
                                                <span class="badge bg-primary">Package: {{ $faq->subparameter->title }}</span>
                                            @else
                                                <span class="badge bg-secondary">General FAQ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $faq->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($faq->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $faq->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $faq->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('faqspackages.destroy', $faq) }}" method="POST"
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
                                        <td colspan="5" class="text-center py-5 text-muted">No FAQs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Load Parameters & Packages --}}
    @php
        $parameters = \App\Models\Parameter::active()->orderBy('title')->get();
        $packages = \App\Models\Subparameter::active()->orderBy('title')->get();
    @endphp

    {{-- ADD FAQ MODAL --}}
    <div class="modal fade" id="addFaqModal">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('faqspackages.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label>Question <span class="text-danger">*</span></label>
                                <input type="text" name="question" class="form-control" value="{{ old('question') }}"
                                    required>
                            </div>
                            <div class="col-12">
                                <label>Answer <span class="text-danger">*</span></label>
                                <textarea name="answer" class="summernote">{{ old('answer') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Link FAQ to</label>
                                <select name="link_type" class="form-control link-type" required>
                                    <option value="none">General (No link)</option>
                                    <option value="parameter">Parameter</option>
                                    <option value="package">Health Package</option>
                                </select>
                            </div>
                            <div class="col-md-6 parameter-group" style="display:none;">
                                <label>Select Parameter</label>
                                <select name="parameter_id" class="form-control">
                                    <option value="">-- Choose Parameter --</option>
                                    @foreach($parameters as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 package-group" style="display:none;">
                                <label>Select Health Package</label>
                                <select name="subparameter_id" class="form-control">
                                    <option value="">-- Choose Package --</option>
                                    @foreach($packages as $pkg)
                                        <option value="{{ $pkg->id }}">{{ $pkg->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save FAQ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- VIEW & EDIT MODALS --}}
    @foreach($faqs as $faq)
        <!-- View Modal -->
        <div class="modal fade" id="view{{ $faq->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">FAQ Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Table Layout -->
                    <table class="table table-bordered table-striped mb-0">

                        <tr>
                            <th>Question :</th>
                            <td colspan="3">{{ $faq->question }}</td>
                        </tr>

                        <tr>
                            <th>Status :</th>
                            <td colspan="3">
                                <span class="badge bg-{{ $faq->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($faq->status) }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Linked To :</th>
                            <td colspan="3">
                                @if($faq->parameter)
                                    <span class="badge bg-info">Parameter: {{ $faq->parameter->title }}</span>
                                @elseif($faq->subparameter)
                                    <span class="badge bg-primary">Package: {{ $faq->subparameter->title }}</span>
                                @else
                                    <span class="badge bg-secondary">General FAQ</span>
                                @endif
                            </td>
                        </tr>

                    </table>

                    <!-- Answer Section -->
                    <div class="p-3">
                        <p><strong>Answer:</strong></p>
                        <div class="border p-3 rounded ">
                            {!! $faq->answer !!}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Edit Modal -->
        <div class="modal fade" id="edit{{ $faq->id }}">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('faqspackages.update', $faq) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit FAQ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label>Question <span class="text-danger">*</span></label>
                                    <input type="text" name="question" class="form-control"
                                        value="{{ old('question', $faq->question) }}" required>
                                </div>
                                <div class="col-12">
                                    <label>Answer <span class="text-danger">*</span></label>
                                    <textarea name="answer" class="summernote">{{ old('answer', $faq->answer) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Link FAQ to</label>
                                    <select name="link_type" class="form-control link-type" required>
                                        <option value="none" {{ !$faq->parameter_id && !$faq->subparameter_id ? 'selected' : '' }}>General (No link)</option>
                                        <option value="parameter" {{ $faq->parameter_id ? 'selected' : '' }}>Parameter</option>
                                        <option value="package" {{ $faq->subparameter_id ? 'selected' : '' }}>Health Package
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 parameter-group"
                                    style="{{ $faq->parameter_id ? 'display:block;' : 'display:none;' }}">
                                    <label>Select Parameter</label>
                                    <select name="parameter_id" class="form-control">
                                        <option value="">-- Choose Parameter --</option>
                                        @foreach($parameters as $p)
                                            <option value="{{ $p->id }}" {{ old('parameter_id', $faq->parameter_id) == $p->id ? 'selected' : '' }}>
                                                {{ $p->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 package-group"
                                    style="{{ $faq->subparameter_id ? 'display:block;' : 'display:none;' }}">
                                    <label>Select Health Package</label>
                                    <select name="subparameter_id" class="form-control">
                                        <option value="">-- Choose Package --</option>
                                        @foreach($packages as $pkg)
                                            <option value="{{ $pkg->id }}" {{ old('subparameter_id', $faq->subparameter_id) == $pkg->id ? 'selected' : '' }}>
                                                {{ $pkg->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $faq->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $faq->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update FAQ</button>
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
            $('.summernote').summernote({ height: 200 });

            // Search
            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $("#faqTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Delete confirmation
            $('.delete-btn').click(function (e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete FAQ?',
                    text: "This cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });

            // Toggle Parameter / Package fields
            function toggleLinkFields() {
                $('.link-type').each(function () {
                    let val = $(this).val();
                    let modal = $(this).closest('.modal');
                    modal.find('.parameter-group, .package-group').hide();
                    modal.find('[name="parameterparameter_id"], [name="subparameter_id"]').prop('required', false);

                    if (val === 'parameter') {
                        modal.find('.parameter-group').show();
                        modal.find('[name="parameter_id"]').prop('required', true);
                    } else if (val === 'package') {
                        modal.find('.package-group').show();
                        modal.find('[name="subparameter_id"]').prop('required', true);
                    }
                });
            }

            $(document).on('change', '.link-type', toggleLinkFields);
            toggleLinkFields();
        });


        const searchInput = document.getElementById('testSearch');
        const tableBody = document.getElementById('testTableBody');
        searchInput.addEventListener('keyup', function () {

            let keyword = this.value.trim();

            fetch(`/health-risks/search?keyword=${keyword}`)
                .then(res => res.json())
                .then(res => {

                    let html = '';

                    if (res.data.length > 0) {

                        res.data.forEach(item => {
                            html += `
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkItem" value="${item.id}">
                                        </td>

                                        <td>${highlight(item.quesion, keyword)}</td>

                                        <td class="text-primary">${highlight(item.status, keyword)}</td>

                                        <td>
         <a href="javascript:void(0)" data-id="${item.id}" 
                                           class="viewApp btn btn-sm btn-info light">
                                           <i class="fa fa-eye"></i>
                                        </a>

                                            <a href="javascript:void(0)" data-id="${item.id}" 
                                               class="editApp btn btn-sm btn-warning light">
                                               <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="javascript:void(0)" data-id="${item.id}" 
                                               class="deleteContact btn btn-sm btn-danger light">
                                               <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                `;
                        });

                    } else {
                        html = `
                                <tr>
                                    <td colspan="6" class="text-center text-danger">
                                        No related search
                                    </td>
                                </tr>
                            `;
                    }

                    tableBody.innerHTML = html;
                });
        });
        function highlight(text, keyword) {
            if (!keyword) return text;

            const regex = new RegExp(`(${keyword})`, 'gi');
            return text.replace(regex, `<mark>$1</mark>`);
        }
    </script>
@endpush