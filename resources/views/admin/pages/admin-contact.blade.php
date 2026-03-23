@extends("admin.layout.admin-master")
@section("title", "Contact us | Continuity Care Dashboard")
@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Contact Us</a></li>
                </ol>
            </div>
            <div class="form-head d-flex mb-3 mb-md-4 align-items-center">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="contactSearch" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" id="searchBtn" class="input-group-text"><i
                                class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <div class="ms-auto">
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
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contactTableBody">
                                        {{-- @foreach($contact as $lead)
                                        <tr>
                                            <td>
                                                <div class="checkbox text-end align-self-center">
                                                    <div class="form-check custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input checkItem"
                                                            value="{{ $lead->id }}" required="">
                                                        <label class="form-check-label" for="checkbox"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="patient-info ps-0">

                                                <span class="text-nowrap ms-2">{{ $lead->fullname }}</span>
                                            </td>
                                            <td class="text-primary">{{ $lead->email }}</td>
                                            <td>{{ $lead->phone }}</td>


                                            <td>
                                                <span class="me-3">
                                                    <a href="javascript:void(0);" data-id="{{ $lead->id }}"
                                                        class="viewContactbtn btn btn-sm btn-info light"><i
                                                            class=" fa fa-eye fs-18"></i></a>
                                                </span>
                                                <span class="me-3">
                                                    <a href="javascript:void(0);" data-id="{{ $lead->id }}"
                                                        class="editContactbtn btn btn-sm btn-warning light"><i
                                                            class="fa fa-pencil fs-18"></i></a>
                                                </span>

                                                <span>
                                                    <a class=" btn btn-sm btn-danger light   deleteApp"
                                                        data-id="{{ $lead->id }}"><i class="fa fa-trash fs-18"></i></a>
                                                </span>

                                            </td>
                                        </tr>

                                        @endforeach --}}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- for view Appointment --}}
        <div class="modal fade" id="viewContact" tabindex="-1" aria-labelledby="viewContactLabel" aria-hidden="true">
            <div class="modal-dialog custom-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewContactLabel">View Contact Lead</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table table-bordered table-striped mb-0">

                            <tr>
                                <th>
                                    Name :
                                </th>
                                <td id="c_name"></td>
                                <th>
                                    Email
                                </th>
                                <td id="c_email"></td>
                            </tr>
                            <tr>
                                <th>
                                    Phone :
                                </th>
                                <td id="c_phone"></td>
                                <th>
                                    Subject :
                                </th>
                                <td id="c_subject"></td>
                            </tr>
                            <tr>
                                <th>
                                    Message :
                                </th>
                                <td id="c_message" class="content" colspan="3"></td>
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
        <div class="modal fade" id="editContact" tabindex="-1" aria-labelledby="editContact" aria-hidden="true">
            <div class="modal-dialog custom-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editContactLabel">Edit Contact</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="updateContactForm">
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
                                        <input type="text" name="phone" class="form-control" id="edit_mobile">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Subject:</label>
                                        <input type="text" name="subject" class="form-control" id="edit_subject">
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Message :</label>
                                        <textarea class="form-control" name="message" id="edit_message"></textarea>
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
        // VIEW CONTACT
        $(document).on("click", ".viewContactbtn", function () {

            let id = $(this).data("id");

            $.ajax({
                url: "/contacts/view/" + id,
                type: "GET",
                success: function (res) {

                    $('#c_name').text(res.fullname);
                    $('#c_email').text(res.email);
                    $('#c_phone').text(res.phone);
                    $('#c_subject').text(res.subject);
                    $('#c_message').html(res.message);

                    let viewModal = new bootstrap.Modal(document.getElementById('viewContact'));
                    viewModal.show();
                }
            });
        });


        // EDIT CONTACT
        $(document).on("click", ".editContactbtn", function () {

            let id = $(this).data("id");

            $.ajax({
                url: "/contacts/view/" + id,
                type: "GET",
                success: function (res) {

                    $('#edit_id').val(res.id);
                    $('#edit_fullname').val(res.fullname);
                    $('#edit_email').val(res.email);
                    $('#edit_mobile').val(res.phone);
                    $('#edit_subject').val(res.subject);
                    $('#edit_message').val(res.message);

                    let editModal = new bootstrap.Modal(document.getElementById('editContact'));
                    editModal.show();
                }
            });
        });

        $('#updateContactForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "/contacts/update",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire("Updated!", "Contact Lead updated successfully!", "success");
                    $('#editContact').modal('hide');
                    location.reload();
                }
            });

        });
        $(document).on("click", ".deleteApp", function () {


            let id = $(this).data("id");
            let row = $(this).closest("tr");

            Swal.fire({
                title: "Are you sure?",
                text: "This Contact Lead will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ url('/contacts/delete') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {

                            Swal.fire("Deleted!", "Contact Query removed successfully.", "success");

                            // remove row
                            row.fadeOut(600, function () {
                                $(this).remove();
                            });
                        }
                    });

                }
            });

        });



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
                Swal.fire("Oops!", "Please select at least one contact.", "warning");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Selected Contact will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete selected!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "/contacts/delete-selected",

                        type: "POST",
                        data: {
                            ids: selected,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            Swal.fire("Deleted!", "Selected contact removed.", "success");

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


        // search functionality

        const searchInput = document.getElementById('contactSearch');
        const tableBody = document.getElementById('contactTableBody');

        searchInput.addEventListener('keyup', function () {

            let keyword = this.value.trim();

            fetch(`/contacts/search?keyword=${keyword}`)
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
                                               class="viewContact btn btn-sm btn-info light">
                                               <i class="fa fa-eye"></i>
                                            </a>

                                            <a href="javascript:void(0)" data-id="${item.id}" 
                                               class="editContact btn btn-sm btn-warning light">
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
    <script>
        const API_BASE = "http://localhost:8000/api";

        function loadLeads() {
            fetch(`${API_BASE}/leads`)
                .then(res => res.json())
                .then(res => {

                    let html = '';

                    res.data.forEach(lead => {
                        html += `
                        <tr>
                            <td>
                                <input type="checkbox" class="checkItem" value="${lead.id}">
                            </td>
                            <td>${lead.fullname}</td>
                            <td class="text-primary">${lead.email}</td>
                            <td>${lead.phone}</td>
                            <td>
                                <a href="javascript:void(0)" data-id="${lead.id}"
                                   class="viewContactbtn btn btn-sm btn-info light">
                                   <i class="fa fa-eye"></i>
                                </a>

                                <a href="javascript:void(0)" data-id="${lead.id}"
                                   class="editContactbtn btn btn-sm btn-warning light">
                                   <i class="fa fa-pencil"></i>
                                </a>

                                <a href="javascript:void(0)" data-id="${lead.id}"
                                   class="deleteLead btn btn-sm btn-danger light">
                                   <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>`;
                    });

                    document.getElementById('contactTableBody').innerHTML = html;
                });
        }

        document.addEventListener("DOMContentLoaded", loadLeads);

        $(document).on("click", ".viewContactbtn", function () {

            let id = $(this).data("id");

            $.get(`http://localhost:8000/api/leads/${id}`, function (res) {

                $('#c_name').text(res.fullname);
                $('#c_email').text(res.email);
                $('#c_phone').text(res.phone);
                $('#c_subject').text(res.subject);
                $('#c_message').html(res.message);

                new bootstrap.Modal('#viewContact').show();
            });
        });


    </script>

@endpush