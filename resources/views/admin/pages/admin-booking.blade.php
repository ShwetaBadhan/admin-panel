@extends("admin.layout.admin-master")
@section("title", "Booking Leads | Continuity Care Dashboard")
@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Booking Leads</a></li>
                </ol>
            </div>
            <div class="form-head d-flex mb-3 mb-md-4 align-items-center">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" id="bookingSearch" class="form-control" placeholder="Search here">
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
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bookingTableBody">
                                        @forelse($bookings as $booking)
                                            <tr>
                                                <td>
                                                    <div class="checkbox text-end align-self-center">
                                                        <div class="form-check custom-checkbox ">
                                                            <input type="checkbox" class="form-check-input checkItem"
                                                                value="{{ $booking->id }}" required="">
                                                            <label class="form-check-label" for="checkbox"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="patient-info ps-0">

                                                    <span class="text-nowrap ms-2">{{ $booking->name }}</span>
                                                </td>

                                                <td>{{ $booking->mobile }}</td>


                                                <td>


                                                    <span>
                                                        <a class="btn btn-sm btn-danger light deleteBook"
                                                            data-id="{{ $booking->id }}">
                                                            <i class="fa fa-trash fs-18"></i>
                                                        </a>

                                                    </span>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">

                                                    No Booking Leads Found
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        // booking delelte
        $(document).on("click", ".deleteBook", function () {

            let id = $(this).data("id");
            let row = $(this).closest("tr");

            Swal.fire({
                title: "Are you sure?",
                text: "This Booking Lead will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ url('/booking-lead/delete') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {

                            Swal.fire("Deleted!", "Booking Query removed successfully.", "success");

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
                    Swal.fire("Oops!", "Please select at least one Booking.", "warning");
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "Selected Booking will be deleted permanently!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete selected!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "/booking-leads/delete-selected",

                            type: "POST",
                            data: {
                                ids: selected,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                Swal.fire("Deleted!", "Selected Booking removed.", "success");

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

        const searchInput = document.getElementById('bookingSearch');
        const tableBody = document.getElementById('bookingTableBody');
        searchInput.addEventListener('keyup', function () {

            let keyword = this.value.trim();

            fetch(`/booking-lead/search?keyword=${keyword}`)
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
                                <td>${highlight(item.name, keyword)}</td>

                                <td>${highlight(item.mobile, keyword)}</td>
                                <td>


                                    <a href="javascript:void(0)" data-id="${item.id}" 
                                       class="deleteBook btn btn-sm btn-danger light">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                        });

                    } else {
                        html = `
                        <tr>
                            <td colspan="4" class="text-center text-danger">
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