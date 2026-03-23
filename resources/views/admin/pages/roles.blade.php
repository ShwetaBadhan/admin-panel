@extends("admin.layout.admin-master")
@section("title", "Roles | Continuity Care")
@php
    use Spatie\Permission\Models\Role;
    use Spatie\Permission\Models\Permission;
@endphp

@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Roles List</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
                <div class="me-auto d-lg-block">
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        + Add New Role
                    </button>
                </div>
                <div class="input-group search-area ms-auto d-inline-flex me-2">
                    <input type="text" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
            </div>

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
            @if(session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('error') }}',
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
                                    <th>Role Name</th>
                                    <th>Permissions Count</th>
                                    <th>Users Count</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
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
                                        <td>
                                            <strong>{{ ucfirst($role->name) }}</strong>
                                            @if($role->name === 'admin')
                                                <span class="badge badge-danger">Super Admin</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $role->permissions->count() }}</span> permissions
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $role->users->count() }}</span> users
                                        </td>
                                        <td>{{ $role->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <!-- Edit Permissions Button -->
                                                <button class="btn btn-sm btn-info light me-2" data-bs-toggle="modal"
                                                    data-bs-target="#editRolePermissionsModal{{ $role->id }}">
                                                    <i class="fas fa-shield"></i>
                                                </button>
                                                <!-- Edit Role Name Button -->
                                                <button class="btn btn-sm btn-warning light me-2" data-bs-toggle="modal"
                                                    data-bs-target="#editRoleNameModal{{ $role->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @if($role->name !== 'admin')
                                                    <!-- Delete Button -->
                                                    <button class="btn btn-sm btn-danger light" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRoleModal{{ $role->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Role Name Modal -->
                                    <div class="modal fade" id="editRoleNameModal{{ $role->id }}">
                                        <div class="modal-dialog">
                                            <form action="{{ route('roles.update', $role) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Edit Role Name: {{ $role->name }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Role Name:</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ $role->name }}" required>
                                                            <small class="text-muted">Cannot rename 'admin' role</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Role Name</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Edit Role Permissions Modal -->
                                    <div class="modal fade" id="editRolePermissionsModal{{ $role->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <form action="{{ route('roles.permissions.update', $role) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Edit Permissions for: {{ ucfirst($role->name) }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            @foreach($permissions->chunk(4) as $chunk)
                                                                @foreach($chunk as $permission)
                                                                    <div class="col-md-3 mb-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="permissions[]" value="{{ $permission->name }}"
                                                                                id="perm_{{ $permission->id }}_{{ $role->id }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                                            <label class="form-check-label small"
                                                                                for="perm_{{ $permission->id }}_{{ $role->id }}">
                                                                                {{ $permission->name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update
                                                            Permissions</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Delete Role Modal -->
                                    <div class="modal fade" id="deleteRoleModal{{ $role->id }}">
                                        <div class="modal-dialog">
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Delete Role</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete the role
                                                            <strong>{{ $role->name }}</strong>?</p>
                                                        <p class="text-danger"><small>This action cannot be undone. All users
                                                                with this role will lose their role assignment.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete Role</button>
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

    <!-- Add Role Modal WITH PERMISSION ASSIGNMENT -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleLabel">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="col-form-label">Role Name:</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="e.g. Doctor, Manager, Receptionist" required>
                            <small class="text-muted">Role names should be singular (Doctor, not Doctors)</small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="col-form-label">Assign Permissions:</label>
                            <div class="row"
                                style="max-height: 300px; overflow-y: auto; border: 1px solid #eee; padding: 15px;">
                                @foreach($permissions->chunk(4) as $chunk)
                                    @foreach($chunk as $permission)
                                        <div class="col-md-3 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $permission->name }}" id="perm_add_{{ $permission->id }}">
                                                <label class="form-check-label small" for="perm_add_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                            <small class="text-muted">Select permissions to assign to this role</small>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger light me-3" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Role with Permissions</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection