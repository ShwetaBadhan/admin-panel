@extends("admin.layout.admin-master")
@section("title", "Appointment | Continuity Care Dashboard")
@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Appointment</a></li>
                </ol>
            </div>
            <div class="form-head d-flex mb-3 mb-md-4 align-items-center">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="appointmentSearch" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" id="searchBtn" class="input-group-text"><i
                                class="flaticon-381-search-2"></i></button>
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

                                <table id="example5"
                                    class="table table-striped patient-list mb-4 dataTablesCard fs-14 display" id="example3"
                                    style="min-width: 845px">
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
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appointmentTableBody">
                                        @foreach($appointments as $appointment)
                                            <tr>
                                                <td>
                                                    <div class="checkbox text-end align-self-center">
                                                        <div class="form-check custom-checkbox ">
                                                            <input type="checkbox" class="form-check-input checkItem"
                                                                value="{{ $appointment->id }}" required="">
                                                            <label class="form-check-label" for="checkbox"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="patient-info ps-0">

                                                    <span class="text-nowrap ms-2">{{ $appointment->fullname }}</span>
                                                </td>
                                                <td class="text-primary">{{ $appointment->email }}</td>
                                                <td>{{ $appointment->phone }}</td>


                                                <td>
                                                    <span class="me-3">
                                                        <a href="javascript:void(0);" data-id="{{ $appointment->id }}"
                                                            class="viewApp btn btn-sm btn-info light "><i
                                                                class=" fa fa-eye fs-18"></i></a>
                                                    </span>
                                                    <span class="me-3">
                                                        <a href="javascript:void(0);" data-id="{{ $appointment->id }}"
                                                            class="editApp btn btn-sm btn-warning light"><i
                                                                class="fa fa-pencil fs-18"></i></a>
                                                    </span>

                                                    <span>
                                                        <a class="btn btn-sm btn-danger light deleteContact"
                                                            data-id="{{ $appointment->id }}">
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
        {{-- book Appointment modal --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Book Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/appointment-store') }}" method="POST" id="appointmentForm">
                            <!-- #region -->

                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="form-floating field-inner">
                                            <input class="form-control" id="fullname" name="fullname"
                                                placeholder="Full Name Here" type="text" autocomplete="off"
                                                required="required" value="{{ old('fullname') }}">
                                            @error('fullname')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror

                                            <label for="fullname"><i class="fa-solid fa-user"></i> Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="form-floating field-inner">
                                            <input class="form-control" id="email" name="email" placeholder="Email Here"
                                                type="email" autocomplete="off" required="required"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror

                                            <label for="email"><i class="fa-solid fa-envelope"></i> Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="form-floating field-inner">
                                            <input class="form-control" name="phone" type="text" autocomplete="off"
                                                required="required" value="{{ old(key: 'phone') }}">
                                            @error('phone')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                            <label for="phone"><i class="fa-solid fa-phone"></i> Phone</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="form-floating field-inner">
                                            <input class="form-control" id="appointmentdate" name="appointmentdate"
                                                placeholder="dd-mm-yyyy" autocomplete="off" required="required" type="text"
                                                value="{{ old('appointmentdate') }}">
                                            <label for="appointmentdate"><i class="fa-solid fa-calendar-days"></i>
                                                Appointment Date</label>


                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-floating field-inner">
                                            <textarea id="message" class="form-control" name="message"
                                                placeholder="Messege Here" autocomplete="off"
                                                required="required"> {{ old('message') }}</textarea>
                                            <label for="message"><i class="fa-solid fa-message"></i> Message</label>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                        <h5 class="modal-title" id="viewAppointmentLabel">View Appointment</h5>
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
                                    Package :
                                </th>
                                <td id="a_selectdepartment"></td>
                            </tr>
                            <tr>
                                <th>
                                    Appointment Date :
                                </th>
                                <td id="a_appointmentdate" colspan="3"></td>

                            </tr>
                            <tr>

                                <th>
                                    message
                                </th>
                                <td id="a_message" colspan="3"></td>
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
                        <h5 class="modal-title" id="editAppointmentLabel">Edit Appointment</h5>
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
                                        <label class="col-form-label">Select Department:</label>
                                        <input type="text" name="selectdepartment" class="form-control"
                                            id="edit_selectdepartment">
                                    </div>
                                </div>


                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Appointment Date</label>
                                        <input type="date" name="appointmentdate" class="form-control"
                                            id="edit_appointmentdate" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Message :</label>
                                        <textarea class="form-control" name="message" id="edit_message" readonly></textarea>
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




        $(document).on('click', '.deleteContact', function () {
            let id = $(this).data("id");
            let row = $(this).closest("tr");

            Swal.fire({
                title: "Are you sure?",
                text: "This Appointment will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/appointment/delete/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function () {
                            Swal.fire("Deleted!", "Appointment removed.", "success");
                            row.fadeOut(400, function () {
                                $(this).remove();
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.editApp', function () {
            let id = $(this).data('id');

            $.ajax({
                url: "/appointment/view/" + id,
                type: "GET",
                success: function (res) {

                    $('#edit_id').val(res.id);
                    $('#edit_fullname').val(res.fullname);
                    $('#edit_email').val(res.email);
                    $('#edit_phone1').val(res.phone);

                    $('#edit_selectdepartment').val(res.selectdepartment);
                    $('#edit_appointmentdate').val(res.appointmentdate);
                    $('#edit_message').val(res.message);

                    $('#editAppointment').modal('show');
                }
            });
        });
        $(document).on('click', '.viewApp', function () {
            let id = $(this).data('id');

            $.ajax({
                url: "/appointment/view/" + id,
                type: "GET",
                success: function (res) {
                    $('#a_name').text(res.fullname);
                    $('#a_email').text(res.email);
                    $('#a_phone').text(res.phone);
                    $('#a_selectdepartment').text(res.selectdepartment);
                    $('#a_appointmentdate').text(res.appointmentdate);
                    $('#a_message').html(res.message);

                    $('#viewAppointment').modal('show');
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



                if (selected.length === 0) {
                    Swal.fire("Oops!", "Please select at least one appointment.", "warning");
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "Selected appointments will be deleted permanently!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete selected!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "/appointments/delete-selected",

                            type: "POST",
                            data: {
                                ids: selected,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                Swal.fire("Deleted!", "Selected appointments removed.", "success");

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

        const searchInput = document.getElementById('appointmentSearch');
        const tableBody = document.getElementById('appointmentTableBody');

        searchInput.addEventListener('keyup', function () {

            let keyword = this.value.trim();

            fetch(`/appointments/search?keyword=${keyword}`)
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
                                <td class="text-primary">${highlight(item.email, keyword)}</td>
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