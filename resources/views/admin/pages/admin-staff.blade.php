@extends("admin.layout.admin-master")
@section("title", "Our Staff | Continuity Care")
@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item  active"><a href="javascript:void(0)">Our Staff</a></li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
                <div class=" me-auto  ">
                    <a href="javascript:void(0);" class="btn btn-danger btn-rounded deleteSelected">Delete Selected</a>
                </div>
                <div class="ms-auto d-lg-block">
                    <a href="javascript:void(0);" class="btn btn-primary btn-rounded addBtn">
                        + Add New
                    </a>


                </div>

            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="table-responsive">
                        <table id="example5"
                            class="table shadow-hover doctor-list table-bordered mb-4 dataTablesCard fs-14">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox align-self-center">
                                            <div class="form-check custom-checkbox ">
                                                <input type="checkbox" class="form-check-input" id="checkAll" required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </div>
                                    </th>
                                    <th>Image</th>
                                    <th>Staff Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($staffs as $staff)

                                    <tr>
                                        <td>
                                            <div class="checkbox text-end align-self-center">
                                                <div class="form-check custom-checkbox ">
                                                    <input type="checkbox" class="form-check-input checkItem"
                                                        value="{{ $staff->id }}" required="">
                                                    <label class="form-check-label" for="checkbox"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <img alt="" src="{{ asset('uploads/' . $staff->image) }}" height="43" width="43"
                                                class="rounded-circle ms-4">
                                        </td>


                                        <td>{{ $staff->fullname }}
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($staff->status == 1)
                                                    <span class="text-success font-w600">Available</span>
                                                @else
                                                    <span class="text-danger font-w600">Unavailable</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <span class="me-3">
                                                <a href="javascript:void(0);" class="viewStaff btn btn-sm btn-info light"
                                                    data-id="{{ $staff->id }}">
                                                    <i class="fa fa-eye fs-18"></i>
                                                </a>
                                            </span>
                                            <span class="me-3">
                                                <a href="javascript:void(0)" class="editStaff btn btn-sm btn-warning light"
                                                    data-id="{{ $staff->id }}"><i class="fa fa-pencil fs-18 "></i></a>
                                            </span>
                                            <span>
                                                <a href="javascript:void(0);" class="deleteStaff btn btn-sm btn-danger light"
                                                    data-id="{{ $staff->id }}">
                                                    <i class="fa fa-trash fs-18 "></i>
                                                </a>
                                            </span>

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> No Staff Found</td>
                                    </tr>


                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ADD STAFF MODAL --}}
    <div class="modal fade" id="addAppointment" tabindex="-1" aria-labelledby="addAppointment" aria-hidden="true">
        <div class="modal-dialog custom-modal" role="document">
            <div class="modal-content">
                <div class="modal-header bg-theme-light">
                    <h5 class="modal-title" id="addAppointmentLabel">Add Staff Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    @if(session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: '{{ session("success") }}',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK',
                            });
                        </script>
                    @endif
                    <form action="{{ url('/staff/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Name:</label>
                                    <input type="text" name="fullname" class="form-control" id="name1" placeholder="Name">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <label>Image</label><br>
                                <small>Only png | jpeg | jpg files allowed.</small>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Status:</label>
                                    <select class="form-control" name="status">
                                        <option value="1">Available</option>
                                        <option value="0">Unavailable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Designation:</label>
                                    <input type="text" name="designation" class="form-control" id="" placeholder="Dentist">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Role :</label>
                                    <select name="tag" class="form-control">
                                        <option value="Executive Board">Executive Board</option>
                                        <option value="Leaders">Leader</option>
                                        <option value="Medical Advisory Board">Medical Advisory Board</option>
                                        <option value="Management Team">Management Team</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Facebook</label>
                                <input type="url" name="facebook" class="form-control"
                                    placeholder="https://facebook.com/yourpage">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Instagram</label>
                                <input type="url" name="instagram" class="form-control"
                                    placeholder="https://instagram.com/yourpage">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>LinkedIn</label>
                                <input type="url" name="linkedin" class="form-control"
                                    placeholder="https://linkedin.com/company/yourpage">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Twitter (X)</label>
                                <input type="url" name="twitter" class="form-control"
                                    placeholder="https://twitter.com/yourhandle">
                            </div>
                        </div>
                        <hr>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-danger light me-3" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- VIEW Staff MODAL --}}
    <div class="modal fade show" id="viewModal" tabindex="-1">
        <div class="modal-dialog custom-modal modal-centered">
            <div class="modal-content">

                <div class="modal-header bg-theme-light">
                    <h5 class="modal-title">View Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <table class="table table-bordered table-striped mb-0">
                    <tr>
                        <th>Name :</th>
                        <td id="v_name"></td>

                        <th>Image :</th>
                        <td>
                            <img id="v_image" src="" width="80" class="rounded">
                        </td>
                    </tr>

                    <tr>
                        <th>Status :</th>
                        <td id="v_status"></td>

                        <th>Designation :</th>
                        <td id="v_designation"></td>
                    </tr>

                    <tr>
                        <th>Role :</th>
                        <td colspan="3" id="v_role"></td>


                    </tr>

                    <tr>
                        <th>Facebook :</th>
                        <td id="v_facebook"></td>
                        <th>Instagram :</th>
                        <td id="v_instagram"></td>
                    </tr>
                    <tr>
                        <th>Linkedin :</th>
                        <td id="v_linkedin"></td>
                        <th>Twitter :</th>
                        <td id="v_twitter"></td>
                    </tr>
                </table>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    {{-- EDIT DOCTOR MODAL--}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog custom-modal">
            <div class="modal-content">

                <div class="modal-header bg-theme-light">
                    <h5 class="modal-title">Edit Staff Details </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editStaffForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-xl-6">
                                <label>Name</label>
                                <input type="text" id="edit_fullname" name="fullname" class="form-control">
                            </div>

                            <div class="col-xl-6">
                                <label>Image</label>
                                <input type="file" id="edit_image" name="image" class="form-control">
                                <img id="edit_preview" src="" width="70" class="mt-2 rounded">
                            </div>

                            <div class="col-xl-6">
                                <label>Status</label>
                                <select id="edit_status" name="status" class="form-control">
                                    <option value="1">Available</option>
                                    <option value="0">Unavailable</option>
                                </select>
                            </div>

                            <div class="col-xl-6">
                                <label>Designation</label>
                                <input type="text" id="edit_designation" name="designation" class="form-control">
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Role :</label>
                                    <select id="edit_tag" name="tag" class="form-control">
                                        <option value="Executive Board">Executive Board</option>
                                        <option value="Leaders">Leaders</option>
                                        <option value="Medical Advisory Board">Medical Advisory Board</option>
                                        <option value="Management Team">Management Team</option>
                                        <option value="Others">Others</option>
                                    </select>


                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Facebook</label>
                                <input type="url" id="edit_facebook" name="facebook" class="form-control"
                                    placeholder="https://facebook.com/yourpage">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Instagram</label>
                                <input type="url" id="edit_instagram" name="instagram" class="form-control"
                                    placeholder="https://instagram.com/yourpage">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>LinkedIn</label>
                                <input type="url" id="edit_linkedin" name="linkedin" class="form-control"
                                    placeholder="https://linkedin.com/company/yourpage">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Twitter (X)</label>
                                <input type="url" id="edit_twitter" name="twitter" class="form-control"
                                    placeholder="https://twitter.com/yourhandle">
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>

        $(document).on('click', '.viewStaff', function () {

            var id = $(this).data('id');

            $.ajax({
                url: "{{ url('/staff/view') }}/" + id,
                type: "GET",
                success: function (staff) {

                    // Fill modal data
                    $('#v_name').text(staff.fullname);
                    $('#v_status').text(staff.status == 1 ? 'Available' : 'Unavailable');
                    $('#v_designation').text(staff.designation);
                    $('#v_role').text(staff.tag);
                    $('#v_facebook').text(staff.facebook);
                    $('#v_instagram').text(staff.instagram);
                    $('#v_linkedin').text(staff.linkedin);
                    $('#v_twitter').text(staff.twitter);


                    // Image
                    $('#v_image').attr('src', '/uploads/' + staff.image);
                    // Open modal
                    $('#viewModal').modal('show');
                }
            });

        });
        $(document).on('click', '.editStaff', function () {

            var id = $(this).data('id');

            $.ajax({
                url: "{{ url('/staff/view') }}/" + id,
                type: "GET",
                success: function (staff) {

                    $('#edit_id').val(staff.id);
                    $('#edit_fullname').val(staff.fullname);
                    $('#edit_status').val(staff.status);
                    $('#edit_designation').val(staff.designation);
                    $('#edit_role').val(staff.tag);
                    $('#edit_facebook').val(staff.facebook);
                    $('#edit_instagram').val(staff.instagram);
                    $('#edit_linkedin').val(staff.linkedin);
                    $('#edit_twitter').val(staff.twitter);


                    $('#edit_preview').attr('src', '/uploads/' + staff.image);

                    $('#editModal').modal('show');
                }
            });
        });
        $('#editStaffForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ url('/staff/update') }}",
                data: formData,
                contentType: false,
                processData: false,

                success: function (response) {
                    Swal.fire("Updated!", "Staff updated successfully!", "success");
                    $('#editModal').modal('hide');
                    location.reload();
                }
            });

        });
        $(document).on("click", ".deleteStaff", function () {

            let id = $(this).data("id");
            let row = $(this).closest("tr");

            Swal.fire({
                title: "Are you sure?",
                text: "This Staff Member will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ url('/staff/delete') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {

                            Swal.fire("Deleted!", "Staff Member removed successfully.", "success");

                            // remove row
                            row.fadeOut(600, function () {
                                $(this).remove();
                            });
                        }
                    });

                }
            });

        });

        $(document).ready(function () {

            // SELECT ALL
            $("#checkAll").on("change", function () {
                $(".checkItem").prop("checked", $(this).prop("checked"));
            });

            // DELETE SELECTED
            $('.deleteSelected').click(function () {

                let selected = [];

                $(".checkItem:checked").each(function () {
                    selected.push($(this).val());
                });

                console.log("Selected IDs:", selected); // debug

                if (selected.length === 0) {
                    Swal.fire("Oops!", "Please select at least one staff member.", "warning");
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "Selected Staff Member's will be deleted permanently!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete selected!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "/staff/deleteSelected",

                            type: "POST",
                            data: {
                                ids: selected,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                Swal.fire("Deleted!", "Selected Staff Member's removed.", "success");

                                selected.forEach(id => {
                                    $(`input[value='${id}']`).closest("tr").fadeOut(500, function () {
                                        $(this).remove();
                                    });
                                });
                            },
                            error: function (xhr) {
                                console.log(xhr.responseText);
                                Swal.fire("Error!", "Something went wrong. Check console.", "error");
                            }
                        });

                    }
                });

            });

        });




        document.addEventListener('click', function (e) {
            if (e.target.closest('.addBtn')) {
                const modal = new bootstrap.Modal(
                    document.getElementById('addAppointment')
                );
                modal.show();
            }
        });
    </script>

@endpush