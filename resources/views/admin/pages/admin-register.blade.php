@extends("admin.layout.admin-master")
@section("title", "User | Continuity Care")
@php
    use Spatie\Permission\Models\Role;
@endphp

@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User List</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
                <div class="me-auto d-lg-block">
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        + Add New User
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
                        title: 'error!',
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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
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
                                        <td>#{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($role = $user->getRoleNames()->first())
                                                <span class="badge badge-primary">{{ ucfirst($role) }}</span>
                                            @else
                                                <span class="badge badge-secondary">No Role</span>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @if($user->is_active)
                                            <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td> --}}
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <!-- View Button -->
                                                <button class="btn btn-sm btn-info light me-2" data-bs-toggle="modal"
                                                    data-bs-target="#viewUserModal{{ $user->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <!-- Edit Button -->
                                                <button class="btn btn-sm btn-warning light me-2" data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal{{ $user->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <!-- Assign Role Button -->
                                                <button class="btn btn-sm btn-primary light me-2" data-bs-toggle="modal"
                                                    data-bs-target="#roleModal{{ $user->id }}">
                                                    <i class="fas fa-user-tag"></i>
                                                </button>
                                                <!-- Delete Button -->
                                                <button class="btn btn-sm btn-danger light" data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal{{ $user->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- View User Modal -->
                                    <div class="modal fade" id="viewUserModal{{ $user->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5>User Details: {{ $user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <strong>User ID:</strong><br>
                                                            #{{ $user->id }}
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Status:</strong><br>
                                                            @if($user->is_active)
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-danger">Inactive</span>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <strong>Full Name:</strong><br>
                                                            {{ $user->name }}
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <strong>Email:</strong><br>
                                                            {{ $user->email }}
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <strong>Role:</strong><br>
                                                            @if($role = $user->getRoleNames()->first())
                                                                <span class="badge badge-primary">{{ ucfirst($role) }}</span>
                                                            @else
                                                                <span class="badge badge-secondary">No Role Assigned</span>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Created At:</strong><br>
                                                            {{ $user->created_at->format('M d, Y') }}
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Last Updated:</strong><br>
                                                            {{ $user->updated_at->format('M d, Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit User Modal -->
                                    <div class="modal fade" id="editUserModal{{ $user->id }}">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin-register.update', $user) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Edit User: {{ $user->name }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label>Full Name:</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ $user->name }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label>Email Address:</label>
                                                            <input type="email" name="email" class="form-control"
                                                                value="{{ $user->email }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label>Status:</label>
                                                            <select name="is_active" class="form-control">
                                                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>
                                                                    Active</option>
                                                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>
                                                                    Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label>New Password (Optional):</label>
                                                            <input type="password" name="password" class="form-control"
                                                                placeholder="Leave blank to keep current">
                                                            <small class="text-muted">Minimum 6 characters</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update User</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Role Assignment Modal -->
                                    <div class="modal fade" id="roleModal{{ $user->id }}">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin-register.role.update', $user) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Assign Role to {{ $user->name }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <select name="role" class="form-control">
                                                            <option value="">No Role</option>
                                                            @foreach(Role::all() as $r)
                                                                <option value="{{ $r->name }}" {{ $user->hasRole($r) ? 'selected' : '' }}>
                                                                    {{ ucfirst($r->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted">Select a role to assign to this user</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Role</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Delete User Modal -->
                                    <div class="modal fade" id="deleteUserModal{{ $user->id }}">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin-register.destroy', $user) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Delete User</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete user
                                                            <strong>{{ $user->name }}</strong>?</p>
                                                        <p class="text-danger">
                                                            <small>
                                                                This action cannot be undone.
                                                                @if($user->id === auth()->id())
                                                                    <br><strong>Warning: You are trying to delete your own
                                                                        account!</strong>
                                                                @endif
                                                            </small>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete User</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $users->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-register.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Full Name:</label>
                                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Email Address:</label>
                                    <input type="email" name="email" class="form-control" placeholder="john@example.com"
                                        required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Password:</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password"
                                        required>
                                    <small class="text-muted">Minimum 6 characters</small>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Confirm Password:</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Confirm Password" required>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Role:</label>
                                    <select name="role" class="form-control">
                                        <option value="">No Role</option>
                                        @foreach(Role::all() as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Status:</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger light me-3" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection