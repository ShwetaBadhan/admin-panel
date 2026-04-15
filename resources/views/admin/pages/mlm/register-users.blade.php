@extends('admin.layout.admin-master')
@section('title', 'MLM Users | Continuity Care')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">MLM Users</li>
                </ol>
            </div>

            <!-- Header -->
            <div class="form-head d-flex mb-3 align-items-start">
                <div class="me-auto">
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        + Add Customer
                    </button>
                </div>
                <div class="input-group search-area ms-auto d-inline-flex" style="max-width: 300px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
                    <button class="input-group-text"><i class="fa fas-search"></i></button>
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

            <!-- Table -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="table-responsive">
                        <table id="mlmUsersTable" class="table table-bordered shadow-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Sponsor</th>
                                    {{-- <th>Position</th>
                                    <th>Level</th>
                                    <th>Membership</th> --}}
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>#{{ $user->id }}</td>
                                        <td>
                                            <strong>{{ $user->user_name }}</strong><br>
                                            <small class="text-muted">{{ $user->track_id }}</small>
                                        </td>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td>{{ $user->email }}<br><small class="text-muted">{{ $user->phone }}</small>
                                        </td>
                                        <td>
                                            @if ($user->sponsor)
                                                <span class="badge bg-info">{{ $user->sponsor->user_name }}</span>
                                            @else
                                                <span class="badge bg-primary">ROOT</span>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @if ($user->tree)
                                                <span
                                                    class="badge bg-{{ $user->tree->position === 'left' ? 'success' : ($user->tree->position === 'right' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($user->tree->position) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->tree)
                                                <span class="badge bg-dark">L{{ $user->tree->level }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $user->membership_type === 'DIRECT_SELLER' ? 'primary' : ($user->membership_type === 'PREFERRED_CUSTOMER' ? 'info' : 'secondary') }}">
                                                {{ str_replace('_', ' ', $user->membership_type) }}
                                            </span>
                                        </td> --}}
                                        <td>
                                            @if ($user->is_deleted)
                                                <span class="badge bg-danger">Deleted</span>
                                            @elseif(!$user->is_active)
                                                <span class="badge bg-warning">Inactive</span>
                                            @elseif(!$user->is_verified)
                                                <span class="badge bg-info">Pending</span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                {{-- <a href="#" class="btn btn-sm btn-info light me-2" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a> --}}
                                                <!-- Edit Button (Modal Trigger) -->
                                                <a class="btn btn-sm btn-warning light me-2" title="Edit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal{{ $user->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button" class="btn btn-sm btn-danger light"
                                                    onclick="confirmDelete({{ $user->id }}, '{{ $user->user_name }}')"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Edit User Modal -->
                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit MLM User</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="" method="POST" id="editForm">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body">
                                                        {{-- Errors --}}
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul class="mb-0">
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        <input type="hidden" name="user_id" id="edit_user_id">

                                                        <div class="row">
                                                            <!-- Username (Read-only) -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Username</label>
                                                                <input type="text" name="user_name" class="form-control"
                                                                    value="{{ $user->user_name }}" readonly>
                                                            </div>

                                                            <!-- Track ID (Read-only) -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Track ID</label>
                                                                <input type="text" name="track_id " class="form-control"
                                                                    value="{{ $user->track_id }}" readonly>
                                                            </div>

                                                            <!-- First Name -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">First Name *</label>
                                                                <input type="text" name="first_name" class="form-control"
                                                                    value="{{ $user->first_name }}" required>
                                                            </div>

                                                            <!-- Last Name -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Last Name</label>
                                                                <input type="text" name="last_name"
                                                                    class="form-control" value="{{ $user->last_name }}">
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Email *</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    value="{{ $user->email }}" required>
                                                            </div>

                                                            <!-- Phone -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Phone *</label>
                                                                <input type="tel" name="phone" class="form-control"
                                                                    value="{{ $user->phone }}" pattern="[0-9]{10}"
                                                                    maxlength="10" required>
                                                            </div>


                                                            <!-- Status -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Status</label>
                                                                <select name="is_active" class="form-control"
                                                                    id="edit_is_active">
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Inactive</option>
                                                                </select>
                                                            </div>

                                                            <!-- New Password (Optional) -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">New Password
                                                                    (Optional)</label>
                                                                <input type="password" name="password"
                                                                    class="form-control"
                                                                    placeholder="Leave blank to keep current">
                                                                <small class="text-muted">Min 8 characters</small>
                                                            </div>

                                                            <!-- Confirm Password -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Confirm Password</label>
                                                                <input type="password" name="password_confirmation"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update
                                                            User</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New MLM User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {{-- ✅ Global Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="mb-2"></i>Validation Errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- ✅ Specific Error Messages --}}
                    @if ($errors->has('sponsor_username'))
                        <div class="alert alert-warning">
                             Sponsor username not found: "{{ old('sponsor_username') }}"
                        </div>
                    @endif

                    <form action="{{ route('mlm-users.store') }}" method="POST" id="mlmRegisterForm" novalidate>
                        @csrf

                        <div class="row">
                            <!-- Sponsor Username -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sponsor Username <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                  
                                    <input type="text" name="sponsor_username"
                                        class="form-control @error('sponsor_username') is-invalid @enderror"
                                        value="{{ old('sponsor_username') }}"
                                        placeholder="Enter sponsor username (e.g., Founder01)" required autocomplete="off"
                                        id="sponsor_username">
                                </div>
                                @error('sponsor_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="sponsor-status"></small>
                            </div>

                            <!-- User Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">User Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                   
                                    <input type="text" name="user_name"
                                        class="form-control @error('user_name') is-invalid @enderror"
                                        value="{{ old('user_name') }}" placeholder="Unique username" required>
                                </div>
                                @error('user_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- First Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name') }}" placeholder="First name" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}" placeholder="Last name">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="user@example.com" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    
                                    <input type="tel" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" placeholder="9999999999" pattern="[0-9]{10}"
                                        maxlength="10" required>
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                 
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min 8 characters" required id="password">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('password')">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Min 8 chars, 1 uppercase, 1 number</small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    
                                    <input type="password" name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Re-enter password" required id="password_confirmation">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('password_confirmation')">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="terms"
                                    class="form-check-input @error('terms') is-invalid @enderror" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to Terms & Conditions <span class="text-danger">*</span>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="reset" class="btn btn-warning px-4">Reset</button>
                            <button type="submit" class="btn btn-primary px-5" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i>Register User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Script -->
    @push('scripts')
        <script>
            // Toggle Password
            function togglePwd(fieldId) {
                const input = document.getElementById(fieldId);
                input.type = input.type === 'password' ? 'text' : 'password';
            }

            // Validate Sponsor in Real-time
            document.getElementById('sponsor_username').addEventListener('blur', async function() {
                const username = this.value.trim();
                const statusEl = document.getElementById('sponsor-status');

                if (username.length >= 3) {
                    try {
                        const response = await fetch(
                            `/api/mlm/check-sponsor?username=${encodeURIComponent(username)}`);
                        const data = await response.json();

                        if (data.valid) {
                            statusEl.textContent = `✓ Valid sponsor: ${data.sponsor_name}`;
                            statusEl.className = 'text-success';
                        } else {
                            statusEl.textContent = '✗ Sponsor not found';
                            statusEl.className = 'text-danger';
                        }
                    } catch (error) {
                        console.log('Sponsor check failed:', error);
                    }
                }
            });

            // Form Submit Handler
            document.getElementById('mlmRegisterForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const password = document.getElementById('password').value;
                const confirm = document.getElementById('password_confirmation').value;

                if (password !== confirm) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Mismatch',
                        text: 'Passwords do not match!',
                        timer: 3000
                    });
                    return false;
                }

                // Show loading
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Registering...';
                submitBtn.disabled = true;

                // Debug: Log form data
                const formData = new FormData(this);
                console.log('Form Data:');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }

                // Submit form
                this.submit();

                // Reset button on error (will be reset on page reload if success)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });

            // Search Filter
            document.getElementById('searchInput')?.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#mlmUsersTable tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });

            // Delete Confirmation
            function confirmDelete(userId, userName) {
                Swal.fire({
                    title: 'Delete User?',
                    text: `Are you sure you want to delete "${userName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/mlm-users/${userId}`;
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // On page load - check if modal should be shown with errors
            @if ($errors->any())
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
                    modal.show();

                    // Show error alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: 'Please check the errors below and try again.',
                        timer: 4000
                    });
                });
            @endif
        </script>
    @endpush
@endsection
