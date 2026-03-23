@extends("admin.layout.admin-master")
@section("title", "Career Job Applications | Continuity Care Dashboard")
@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Career Job Applications </a></li>
                </ol>
            </div>
            <div class="form-head d-flex mb-3 mb-md-4 align-items-center">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="jobSearch" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <div class="ms-auto">
                    {{-- <a href="javascript:void(0);" class="btn btn-primary btn-rounded add-appointment"
                        data-bs-toggle="modal" data-bs-target="#exampleModal">+ Book Appointment</a> --}}
                    <a href="javascript:void(0);" class="btn btn-danger btn-rounded deleteSelected">Delete Selected</a>


                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">

                                <table id="example5" class="table table-striped patient-list mb-4 dataTablesCard fs-14">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="checkbox text-end align-self-center">
                                                    <div class="form-check custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input" id="checkAll"
                                                            required="">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>Name</th>
                                            <th>Applied For</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jobTableBody">
                                        @foreach($application as $applications)
                                            <tr>
                                                <td>
                                                    <div class="checkbox text-end align-self-center">
                                                        <div class="form-check custom-checkbox ">
                                                            <input type="checkbox" class="form-check-input checkItem"
                                                                value="{{ $applications->id }}" required="">
                                                            <label class="form-check-label" for="checkbox"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="patient-info ps-0">

                                                    <span class="text-nowrap ms-2">{{ $applications->fullname }}</span>
                                                </td>
                                                <td class="text-primary">{{ $applications->job_title }}</td>
                                                <td>{{ $applications->phone }}</td>


                                                <td>
                                                    <span class="me-3">
                                                        <a href="javascript:void(0);" data-id="{{ $applications->id }}"
                                                            class="viewApp btn btn-sm btn-info light "><i
                                                                class=" fa fa-eye fs-18"></i></a>
                                                    </span>
                                                    <span class="me-3">
                                                        <a href="javascript:void(0);" data-id="{{ $applications->id }}"
                                                            class="editApp btn btn-sm btn-warning light"><i
                                                                class="fa fa-pencil fs-18"></i></a>
                                                    </span>

                                                    <span>
                                                        <a class="btn btn-sm btn-danger light deleteContact"
                                                            data-id="{{ $applications->id }}">
                                                            <i class="fa fa-trash fs-18 "></i></a>
                                                    </span>

                                                </td>
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- for view Appointment --}}
        <div class="modal fade" id="viewAppointment" tabindex="-1" aria-labelledby="viewAppointmentLabel"
            aria-hidden="true">
            <div class="modal-dialog custom-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewAppointmentLabel">View Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table table-bordered table-striped mb-0">

                            <tr>
                                <th>
                                    Name :
                                </th>
                                <td id="a_name"></td>
                                <th>
                                    Email
                                </th>
                                <td id="a_email"></td>
                            </tr>
                            <tr>
                                <th>
                                    Phone :
                                </th>
                                <td id="a_phone"></td>
                                <th>
                                    address :
                                </th>
                                <td id="a_address"></td>
                            </tr>
                            <tr>

                                <th>
                                    Details :
                                </th>
                                <td id="a_details" colspan="3"></td>

                            </tr>
                            <tr>

                                <th>
                                    Resume
                                </th>
                                <td id="a_resume"></td>
                                <th>
                                    Applied For
                                </th>
                                <td id="a_job"></td>
                            </tr>

                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- for edit Appointment --}}
        <div class="modal fade" id="editAppointment" tabindex="-1" aria-labelledby="editAppointment" aria-hidden="true">
            <div class="modal-dialog custom-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAppointmentLabel">Edit Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="updateAppointmentForm">
                            @csrf
                            <input type="hidden" id="edit_id" name="id">

                            <div class="row">

                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Name:</label>
                                        <input type="text" name="fullname" class="form-control" id="edit_fullname">
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Email:</label>
                                        <input type="email" name="email" class="form-control" id="edit_email">

                                    </div>
                                </div>


                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Phone:</label>
                                        <input type="text" name="phone" class="form-control" id="edit_phone1">
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Address:</label>
                                        <input type="text" name="address" class="form-control" id="edit_address">
                                    </div>
                                </div>



                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Details :</label>
                                        <textarea class="form-control" name="details" id="edit_details"></textarea>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Resume :</label>
                                        <div id="edit_resume"></div>
                                        {{-- <textarea class="form-control" name="resume" id="edit_resume"
                                            readonly></textarea> --}}
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '.viewApp', function () {
            let id = $(this).data('id');

            $.ajax({
                url: "/applications/view/" + id,
                type: "GET",
                success: function (res) {
                    $('#a_name').text(res.fullname);
                    $('#a_email').text(res.email);
                    $('#a_phone').text(res.phone);
                    $('#a_address').text(res.address);
                    $('#a_details').text(res.details);

                    // Resume download link
                    if (res.resume) {
                        $('#a_resume').html(`<a href="/storage/${res.resume}" target="_blank" class="btn btn-sm btn-primary">View Resume</a>`);
                    } else {
                        $('#a_resume').text("No resume uploaded");
                    }

                    $('#a_job').text(res.job_title);

                    $('#viewAppointment').modal('show');
                }
            });
        });
        $(document).on('click', '.editApp', function () {
            let id = $(this).data('id');

            $.ajax({
                url: "/applications/view/" + id,
                type: "GET",
                success: function (res) {
                    $('#edit_id').val(res.id);
                    $('#edit_fullname').val(res.fullname);
                    $('#edit_email').val(res.email);
                    $('#edit_phone1').val(res.phone);
                    $('#edit_address').val(res.address);
                    $('#edit_details').val(res.details);
                    // $('#edit_resume').val(res.resume);
                    if (res.resume) {
                        $('#edit_resume').html(`<a href="/storage/${res.resume}" target="_blank" class="btn btn-sm btn-primary">View Resume</a>`);
                    } else {
                        $('#edit_resume').text("No resume uploaded");
                    }

                    $('#editAppointment').modal('show');
                }
            });
        });
        $('#updateAppointmentForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "/applications/update",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    Swal.fire("Updated!", "Application updated successfully!", "success");
                    $('#editAppointment').modal('hide');
                    location.reload();
                }
            });
        });

        $(document).on('click', '.deleteContact', function () {

            let id = $(this).data("id");
            let row = $(this).closest("tr");

            Swal.fire({
                title: "Are you sure?",
                text: "This application will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "/applications/delete/" + id,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function () {
                            Swal.fire("Deleted!", "Application removed successfully.", "success");

                            row.fadeOut(600, function () {
                                $(this).remove();
                            });
                        }
                    });

                }
            });
        });

        $(document).ready(function () {




            /* single delete*/


            $("#checkAll").on("change", function () {
                $(".checkItem").prop("checked", $(this).prop("checked"));
            });


            $('.deleteSelected').click(function () {

                let selected = [];

                $(".checkItem:checked").each(function () {
                    selected.push($(this).val());
                });

                if (selected.length === 0) {
                    Swal.fire("Oops!", "Please select at least one application.", "warning");
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "Selected applications will be deleted permanently!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete selected!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "/applications/delete-selected",
                            type: "POST",
                            data: {
                                ids: selected,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function () {
                                Swal.fire("Deleted!", "Selected applications removed.", "success");

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


        // search functionality

        const searchInput = document.getElementById('jobSearch');
        const tableBody = document.getElementById('jobTableBody');
        searchInput.addEventListener('keyup', function () {

            let keyword = this.value.trim();

            fetch(`/applications/search?keyword=${keyword}`)
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
                                <td>${highlight(item.fullname, keyword)}</td>

                                <td>${highlight(item.job_title, keyword)}</td>
                                <td>${highlight(item.phone, keyword)}</td>
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
                            <td colspan="5" class="text-center text-danger">
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