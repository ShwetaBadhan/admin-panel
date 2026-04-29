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
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bulkRestoreModal">
                                <i class="fas fa-undo me-1"></i>Restore Selected
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal">
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
                                                <th width="40"><input type="checkbox" class="form-check-input" id="checkAll"></th>
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
                                                    <td><input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}"></td>
                                                    <td>#{{ $user->id }}</td>
                                                    <td>
                                                        <strong>{{ $user->user_name }}</strong><br>
                                                        <small class="text-muted">{{ $user->track_id }}</small>
                                                    </td>
                                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                                    <td>{{ $user->email }}<br><small class="text-muted">{{ $user->phone }}</small></td>
                                                    <td>
                                                        @if ($user->sponsor)
                                                            <span class="badge bg-info">{{ $user->sponsor->user_name }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">ROOT</span>
                                                        @endif
                                                    </td>
                                                    <td><small class="text-danger">{{ $user->updated_at->format('M d, Y H:i') }}</small></td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <!-- Restore Button - Opens Modal -->
                                                            <button type="button" class="btn btn-sm btn-success light" 
                                                                    title="Restore"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#restoreModal"
                                                                    data-user-id="{{ $user->id }}"
                                                                    data-user-name="{{ $user->user_name }}">
                                                                <i class="fas fa-undo"></i>
                                                            </button>

                                                            <!-- Permanent Delete Button - Opens Modal -->
                                                            <button type="button" class="btn btn-sm btn-danger light" 
                                                                    title="Permanent Delete"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#deleteModal"
                                                                    data-user-id="{{ $user->id }}"
                                                                    data-user-name="{{ $user->user_name }}">
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

    <!-- ============ MODALS ============ -->

  <!-- Single Restore Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="restoreForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Restore User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Restore <strong id="restoreUserName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Yes, Restore</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Single Permanent Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Permanent Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Permanently delete <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger fw-bold mb-0">⚠️ This cannot be undone!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Restore Modal -->
<div class="modal fade" id="bulkRestoreModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="bulkRestoreForm" method="POST" action="{{ route('recycle-bin.bulk-restore') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Restore Selected</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Restore <strong id="bulkRestoreCount"></strong> user(s)?</p>
                    <div id="bulkRestoreInputs"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Restore All</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="bulkDeleteForm" method="POST" action="{{ route('recycle-bin.bulk-permanent-delete') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Permanently Delete Selected</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Delete <strong id="bulkDeleteCount"></strong> user(s) permanently?</p>
                    <p class="text-danger fw-bold mb-0">⚠️ This cannot be undone!</p>
                    <div id="bulkDeleteInputs"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete All</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    // ===== MODAL DYNAMIC ACTIONS =====
    
    // Single Restore
    document.getElementById('restoreModal')?.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('restoreUserName').textContent = btn.dataset.userName;
        document.getElementById('restoreForm').action = `/recycle-bin/${btn.dataset.userId}/restore`;
    });

    // Single Delete
    document.getElementById('deleteModal')?.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('deleteUserName').textContent = btn.dataset.userName;
        document.getElementById('deleteForm').action = `/recycle-bin/${btn.dataset.userId}/permanent`;
    });

    // Bulk Restore
    document.getElementById('bulkRestoreModal')?.addEventListener('show.bs.modal', function() {
        const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
        if (!ids.length) { alert('Select at least one user'); this.querySelector('[data-bs-dismiss="modal"]').click(); return; }
        
        document.getElementById('bulkRestoreCount').textContent = ids.length;
        const container = document.getElementById('bulkRestoreInputs');
        container.innerHTML = '';
        ids.forEach(id => {
            container.innerHTML += `<input type="hidden" name="user_ids[]" value="${id}">`;
        });
    });

    // Bulk Delete
    document.getElementById('bulkDeleteModal')?.addEventListener('show.bs.modal', function() {
        const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
        if (!ids.length) { alert('Select at least one user'); this.querySelector('[data-bs-dismiss="modal"]').click(); return; }
        
        document.getElementById('bulkDeleteCount').textContent = ids.length;
        const container = document.getElementById('bulkDeleteInputs');
        container.innerHTML = '';
        ids.forEach(id => {
            container.innerHTML += `<input type="hidden" name="user_ids[]" value="${id}">`;
        });
    });
</script>
@endpush