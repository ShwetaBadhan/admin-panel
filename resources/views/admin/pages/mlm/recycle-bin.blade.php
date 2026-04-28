@extends('admin.layout.admin-master')
@section('title', 'Recycle Bin | Continuity Care')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mlm-users.index') }}">MLM Users</a></li>
                    <li class="breadcrumb-item active">Recycle Bin</li>
                </ol>
            </div>

            <!-- Header -->
            <div class="form-head d-flex mb-3 align-items-start">
                <div class="me-auto">
                    <a href="{{ route('mlm-users.index') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
                <div class="input-group search-area ms-auto d-inline-flex" style="max-width: 300px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search deleted users...">
                    <button class="input-group-text"><i class="fas fa-search"></i></button>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Bulk Actions -->
            @if ($deletedUsers->count() > 0)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                                <label class="form-check-label" for="selectAll">Select All</label>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="btnBulkRestore">
                                <i class="fas fa-undo me-1"></i>Restore Selected
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="btnBulkDelete">
                                <i class="fas fa-trash-alt me-1"></i>Permanently Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">🗑️ Recycle Bin ({{ $deletedUsers->total() }} users)</h4>
                        </div>
                        <div class="card-body">
                            @if ($deletedUsers->count() > 0)
                                <div class="table-responsive">
                                    <table id="recycleBinTable" class="table table-bordered shadow-sm">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="40"><input type="checkbox" class="form-check-input"
                                                        id="checkAll"></th>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Sponsor</th>
                                                <th>Deleted On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($deletedUsers as $user)
                                                <tr>
                                                    <td><input type="checkbox" class="form-check-input user-checkbox"
                                                            value="{{ $user->id }}"></td>
                                                    <td>#{{ $user->id }}</td>
                                                    <td>
                                                        <strong>{{ $user->user_name }}</strong><br>
                                                        <small class="text-muted">{{ $user->track_id }}</small>
                                                    </td>
                                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                                    <td>{{ $user->email }}<br><small
                                                            class="text-muted">{{ $user->phone }}</small></td>
                                                    <td>
                                                        @if ($user->sponsor)
                                                            <span
                                                                class="badge bg-info">{{ $user->sponsor->user_name }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">ROOT</span>
                                                        @endif
                                                    </td>
                                                    <td><small
                                                            class="text-danger">{{ $user->updated_at->format('M d, Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <!-- Restore Button -->
                                                            <button type="button" class="btn btn-sm btn-success light"
                                                                title="Restore"
                                                                onclick="confirmRestore({{ $user->id }})">
                                                                <i class="fas fa-undo"></i>
                                                            </button>

                                                            <!-- Permanent Delete Button -->
                                                            <button type="button" class="btn btn-sm btn-danger light"
                                                                title="Permanent Delete"
                                                                onclick="confirmSingleDelete({{ $user->id }}, '{{ $user->user_name }}')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-3">{{ $deletedUsers->links() }}</div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-trash-restore text-success" style="font-size:4rem"></i>
                                    <h5 class="mt-3">Recycle Bin is Empty</h5>
                                    <p class="text-muted">No deleted users found</p>
                                    <a href="{{ route('mlm-users.index') }}" class="btn btn-primary mt-2">Back to Users</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Forms for Actions -->
    <form id="formPermanentDelete" method="POST" style="display:none">@csrf</form>
    <form id="formBulkRestore" method="POST" action="{{ route('bulk-restore') }}" style="display:none">@csrf</form>
    <form id="formBulkDelete" method="POST" action="{{ route('bulk-permanent-delete') }}" style="display:none">@csrf
    </form>
@endsection

@push('scripts')
    <script>
        // Select All
        document.getElementById('selectAll')?.addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
        });
        document.getElementById('checkAll')?.addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
        });

        // Search Filter
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const f = this.value.toLowerCase();
            document.querySelectorAll('#recycleBinTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(f) ? '' : 'none';
            });
        });

        // Single Restore with Swal
        async function confirmRestore(userId) {
            const result = await Swal.fire({
                title: 'Restore User?',
                text: 'This user will be moved back to active users list.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/mlm-users/${userId}/restore`;
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Single Permanent Delete with Swal
        async function confirmSingleDelete(userId, userName) {
            const result = await Swal.fire({
                title: 'Permanent Delete?',
                html: `Permanently delete <strong>${userName}</strong>?<br><br>
               <span class="text-danger fw-bold">⚠️ This cannot be undone!</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Permanently Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                reverseButtons: true
            });
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/mlm-users/${userId}/permanent`;
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Bulk Restore with Swal
        document.getElementById('btnBulkRestore')?.addEventListener('click', async function() {
            const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (!ids.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one user',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
            const result = await Swal.fire({
                title: 'Restore Users?',
                text: `Restore ${ids.length} user(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });
            if (!result.isConfirmed) return;
            const form = document.getElementById('formBulkRestore');
            form.innerHTML = '@csrf';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            form.submit();
        });

        // Bulk Permanent Delete with Swal
        document.getElementById('btnBulkDelete')?.addEventListener('click', async function() {
            const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (!ids.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one user',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
            const result = await Swal.fire({
                title: 'Permanent Delete?',
                html: `Permanently delete <strong>${ids.length} user(s)</strong>?<br><br>
               <span class="text-danger fw-bold">⚠️ This cannot be undone!</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Permanently Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                reverseButtons: true
            });
            if (!result.isConfirmed) return;
            const form = document.getElementById('formBulkDelete');
            form.innerHTML = '@csrf';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            form.submit();
        });
    </script>
@endpush
