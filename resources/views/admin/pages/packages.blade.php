@extends("admin.layout.admin-master")
@section("title", "Packages | Continuity Care")
@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Package Details</a></li>
                </ol>
            </div>
            <div class="form-head d-flex mb-3 mb-md-4 align-items-center">
                <div class="input-group search-area d-inline-flex me-2">
                    <input type="text" class="form-control" placeholder="Search here">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
                <div class="ms-auto">
                    <a href="javascript:void(0);" class="btn btn-primary btn-rounded add-appointment" data-bs-toggle="modal"
                        data-bs-target="#addPackage">+ Add Package</a>

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
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Service</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse($packages as $sub)
                                            <tr>
                                                <td>
                                                    <div class="checkbox text-end align-self-center">
                                                        <div class="form-check custom-checkbox ">
                                                            <input type="checkbox" class="form-check-input" id="customCheckBox1"
                                                                required="">
                                                            <label class="form-check-label" for="customCheckBox1"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="patient-info ps-0">
                                                    <span>
                                                        <img src="images/avatar/1.jpg" alt="">
                                                    </span>
                                                    <span class="text-nowrap ms-2">Health Test</span>
                                                </td>
                                                <td class="text-primary">Female</td>
                                                <td>Tru Health</td>

                                                <td>
                                                    <span class="me-3">
                                                        <a href="" data-bs-toggle="modal" data-bs-target="#viewAppointment"><i
                                                                class="fa fa-eye fs-18"></i></a>
                                                    </span>
                                                    <span class="me-3">
                                                        <a href="" class="edit-appointment" data-bs-toggle="modal"
                                                            data-bs-target="#editAppointment"><i
                                                                class="fa fa-pencil fs-18 "></i></a>
                                                    </span>
                                                    <span>
                                                        <i class="fa fa-trash fs-18 text-danger" aria-hidden="true"></i>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="checkbox text-end align-self-center">
                                                        <div class="form-check custom-checkbox ">
                                                            <input type="checkbox" class="form-check-input" id="customCheckBox1"
                                                                required="">
                                                            <label class="form-check-label" for="customCheckBox1"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="patient-info ps-0">
                                                    <span>
                                                        <img src="images/avatar/1.jpg" alt="">
                                                    </span>
                                                    <span class="text-nowrap ms-2">Health Test</span>
                                                </td>
                                                <td class="text-primary">Female</td>
                                                <td>Tru Health</td>

                                                <td>
                                                    <span class="me-3">
                                                        <a href="" data-bs-toggle="modal" data-bs-target="#viewAppointment"><i
                                                                class="fa fa-eye fs-18"></i></a>
                                                    </span>
                                                    <span class="me-3">
                                                        <a href="" class="edit-appointment" data-bs-toggle="modal"
                                                            data-bs-target="#editAppointment"><i
                                                                class="fa fa-pencil fs-18 "></i></a>
                                                    </span>
                                                    <span>
                                                        <i class="fa fa-trash fs-18 text-danger" aria-hidden="true"></i>
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $subparameters = \App\Models\Subparameter::where('status', 'active')->orderBy('title')->get();
        @endphp

        {{-- book Appointment modal --}}
        <div class="modal fade" id="addPackage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Package</h5>
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
                        <form id="addPackageForm" action="{{ url('/admin-packages/store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Package title<span class="required">*</span></label>
                                        <input type="text" id="add-title" name="title" class="form-control"
                                            placeholder="Test title" required="">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Slug<span class="required"> (Auto-generate)</span></label>
                                        <input type="text" id="add-slug" name="slug" class="form-control"
                                            placeholder="Auto generate by title" required="">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label for="formFile" class="form-label">Image</label>
                                        <input class="form-control" type="file" id="formFile" name="image">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="col-form-label">Sub Parameter <small
                                                class="text-muted">(Multiple)</small></label>
                                        <select name="subparameter_id[]" multiple class="form-control default-select">
                                            <option value="">— No Parameter —</option>
                                            @foreach($subparameters as $parameter)
                                                <option value="{{ $parameter->id }}">{{ $parameter->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Status<span class="required">*</span></label>
                                        <select class="default-select form-control wide mb-3" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">InActive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label class="text-label">Description<span class="required">*</span></label>
                                        <textarea id="place" name="description" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Send message</button>
                                </div>

                            </div>


                        </form>
                    </div>

                </div>
            </div>
        </div>
        {{-- for view Appointment --}}
        <div class="modal fade" id="viewAppointment" tabindex="-1" aria-labelledby="viewAppointmentLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewAppointmentLabel">View Test</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table table-bordered table-striped mb-0">

                            <tr>
                                <th>
                                    Title :
                                </th>
                                <td>
                                    Test
                                </td>
                                <th>
                                    Image
                                </th>
                                <td>
                                    <img src="images/avatar/1.jpg" alt=""
                                        style="border-radius: 50% ; height:50px ; width:50px;">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Category :
                                </th>
                                <td>
                                    test
                                </td>
                                <th>
                                    Service
                                </th>
                                <td>
                                    test
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Charges :
                                </th>
                                <td>
                                    345
                                </td>
                                <th>
                                    Parameters :
                                </th>
                                <td>
                                    23
                                </td>

                            </tr>
                            <tr>

                                <th>
                                    Description
                                </th>
                                <td id="content" colspan="3">
                                    bla bla
                                </td>
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
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAppointmentLabel">Edit Test</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Test title<span class="required">*</span></label>
                                        <input type="text" id="add-title" name="title" class="form-control"
                                            placeholder="Test title" required="">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Slug<span class="required"> (Auto-generate)</span></label>
                                        <input type="text" id="add-slug" name="slug" class="form-control"
                                            placeholder="Auto generate by title" required="">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label for="formFile" class="form-label">Image</label>
                                        <input class="form-control" type="file" id="formFile">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label"> Category<span class="required">*</span></label>
                                        <select class="default-select form-control wide mb-3">
                                            <option>Category 1</option>
                                            <option>Category 2</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label"> Services<span class="required">*</span></label>
                                        <select class="default-select form-control wide mb-3">
                                            <option>Service 1</option>
                                            <option>Service 2</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Status<span class="required">*</span></label>
                                        <select class="default-select form-control wide mb-3">
                                            <option>Active</option>
                                            <option>InActive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label class="text-label">Description<span class="required">*</span></label>
                                        <textarea id="place" name="place" class="form-control"></textarea>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                </div>
                            </div>


                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Send message</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection