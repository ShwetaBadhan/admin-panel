@extends("admin.layout.admin-master")
@section("title", "Permissions | Continuity Care")
@php
    use Spatie\Permission\Models\Permission;
@endphp

@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Permissions List</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
                <div class="me-auto d-lg-block">
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                        + Add New Permission
                    </button>
                </div>
                <div class="input-group search-area ms-auto d-inline-flex me-2">
                    <input type="text" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
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
                <div class="col-xl-12">
                    <div class="table-responsive">
                        <table id="example5"
                            class="table shadow-hover doctor-list table-bordered mb-4 dataTablesCard fs-14">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox align-self-center">
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll" required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </div>
                                    </th>
                                    <th>Permission Name</th>
                                    <th>Roles Using This</th>
                                    <th>Guard Name</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="checkbox text-end align-self-center">
                                                    <div class="form-check custom-checkbox">
                                                        <input type="checkbox" class="form-check-input">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code>{{ $permission->name }}</code></td>
                                        <td>
                                            @php
                                                $roleCount = $permission->roles->count();
                                            @endphp
                                            @if($roleCount > 0)
                                                <span class="badge badge-success">{{ $roleCount }} role(s)</span>
                                                <small class="text-muted d-block">
                                                    @foreach($permission->roles->take(3) as $role)
                                                        {{ $role->name }}@if(!$loop->last), @endif
                                                    @endforeach
                                                    @if($roleCount > 3)
                                                        +{{ $roleCount - 3 }} more
                                                    @endif
                                                </small>
                                            @else
                                                <span class="badge badge-secondary">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $permission->guard_name }}</td>
                                        <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#editPermissionModal{{ $permission->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger light" data-bs-toggle="modal"
                                                data-bs-target="#deletePermissionModal{{ $permission->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Permission Modal -->
                                    <div class="modal fade" id="editPermissionModal{{ $permission->id }}">
                                        <div class="modal-dialog">
                                            <form action="{{ route('permissions.update', $permission) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Edit Permission</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Permission Name:</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ $permission->name }}" required>
                                                            <small class="text-muted">Use kebab-case: view-users, edit-doctors,
                                                                delete-reports</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Permission</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Delete Permission Modal -->
                                    <div class="modal fade" id="deletePermissionModal{{ $permission->id }}">
                                        <div class="modal-dialog">
                                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Delete Permission</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete the permission
                                                            <code>{{ $permission->name }}</code>?</p>
                                                        <p class="text-danger"><small>This will remove this permission from all
                                                                roles that have it.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete Permission</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPermissionLabel">Add New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('permissions.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Permission Name:</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="e.g. view-appointments, edit-patients" required>
                            <small class="text-muted">Use kebab-case format (lowercase with hyphens)</small>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger light me-3" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Permission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection