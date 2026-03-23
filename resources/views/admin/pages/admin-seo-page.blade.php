@extends("admin.layout.admin-master")
@section("title", "SEO Pages | Continuity Care")
@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item  active"><a href="javascript:void(0)">SEO Pages List</a></li>
                </ol>
            </div>

            <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
                <div class="me-auto d-lg-block">
                    <a href="javascript:void(0);" class="btn btn-primary btn-rounded" data-bs-toggle="modal"
                        data-bs-target="#addAppointment">+ Add New</a>
                </div>
                <div class="input-group search-area ms-auto d-inline-flex me-2">
                    <input type="text" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
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

                                    <th>Page</th>
                                    <th>Status</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seoPages as $seoPage)

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="checkbox text-end align-self-center">
                                                    <div class="form-check custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </div>



                                            </div>
                                        </td>



                                        <td>{{ $seoPage->page }}
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($seoPage->is_active == 1)
                                                    <span class="text-success font-w600">Available</span>
                                                @else
                                                    <span class="text-danger font-w600">Unavailable</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- <td>
                                            <span class="me-3">
                                                <a href="javascript:void(0)" class="editDoctor btn btn-sm btn-warning light"
                                                    data-id="{{ $seoPage->id }}"><i class="fa fa-pencil fs-18 "></i></a>
                                            </span>
                                            <span>
                                                <a href="javascript:void(0);" class="deleteDoctor btn btn-sm btn-danger light"
                                                    data-id="{{ $seoPage->id }}">
                                                    <i class="fa fa-trash fs-18 "></i>
                                                </a>
                                            </span>



                                        </td> --}}

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ADD DOCTOR MODAL --}}
    <div class="modal fade" id="addAppointment" tabindex="-1" aria-labelledby="addAppointment" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAppointmentLabel">Add SEO Page</h5>
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
                    <form action="{{ url('/seo-pages/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Pages:</label>
                                    <input type="text" name="page" class="form-control" id="page" placeholder="Name">
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Status:</label>
                                    <select class="form-control" name="is_active">
                                        <option value="1">Available</option>
                                        <option value="0">Unavailable</option>
                                    </select>
                                </div>
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



    {{-- EDIT DOCTOR MODAL--}}
    <div class="modal fade" id="editAppointment" tabindex="-1">
        <div class="modal-dialog modal-lg modal-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Doctor Details </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editDoctorForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-xl-6">
                                <label>Page</label>
                                <input type="text" id="edit_page" name="page" class="form-control">
                            </div>



                            <div class="col-xl-6">
                                <label>Status</label>
                                <select id="edit_status" name="is_active" class="form-control">
                                    <option value="1">Available</option>
                                    <option value="0">Unavailable</option>
                                </select>
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

@endsection
@push('Scripts')
    <script>


        $(document).on('click', '.editDoctor', function () {

            var id = $(this).data('id');

            $.ajax({
                url: "{{ url('/seo-pages/view') }}/" + id,
                type: "GET",
                success: function (doctor) {

                    $('#edit_id').val(doctor.id);
                    $('#edit_page').val(doctor.page);
                    $('#edit_status').val(doctor.status);


                    $('#editAppointment').modal('show');
                }
            });
        });
        $('#editDoctorForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ url('/seo-pages/update') }}",
                data: formData,
                contentType: false,
                processData: false,

                success: function (response) {
                    Swal.fire("Updated!", "seo updated successfully!", "success");
                    $('#editAppointment').modal('hide');
                    location.reload();
                }
            });

        });
        $(document).on("click", ".deleteDoctor", function () {

            let id = $(this).data("id");
            let row = $(this).closest("tr");

            Swal.fire({
                title: "Are you sure?",
                text: "This SEO will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ url('/seo-pages/delete') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {

                            Swal.fire("Deleted!", "SEO removed successfully.", "success");

                            // remove row
                            row.fadeOut(600, function () {
                                $(this).remove();
                            });
                        }
                    });

                }
            });

        });
    </script>



@endpush