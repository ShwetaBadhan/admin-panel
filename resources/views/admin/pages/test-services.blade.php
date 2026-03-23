@extends("admin.layout.admin-master")
@section("title", "Services | Continuity Care")
@section("content")
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Test Service</a></li>
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
                        data-bs-target="#exampleModal">+ Add Service</a>

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

                                            <th>Service</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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

                                                <span class="text-nowrap ms-2">Health Test</span>
                                            </td>
                                            <td class="text-primary">Active</td>

                                            <td>

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

                                                <span class="text-nowrap ms-2">Health Test</span>
                                            </td>
                                            <td class="text-primary">Active</td>

                                            <td>

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
                        <h5 class="modal-title" id="exampleModalLabel">Add Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form action="{{ route('services.store') }}" method="POST" id="addService">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Service Title<span class="required">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Parsley"
                                            required="">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Status<span class="required">*</span></label>
                                        <select name="status" class="default-select form-control wide mb-3">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="text-label">Icon<span class="required">*</span></label>
                                        <input class="form-control" name="icon" type="file" id="formFile">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-label">Parameters<span class="required">*</span></label>

                                        <div id="packagesWrapper"></div>

                                        <div class="d-flex gap-2 mt-2">
                                            <input type="text" id="addPackage" class="form-control"
                                                placeholder="Enter Package" />
                                            <button type="button" class="btn btn-primary" id="addPackageBtn"> Add
                                            </button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Send message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- for edit Appointment --}}
        <div class="modal fade" id="editAppointment" tabindex="-1" aria-labelledby="editAppointment" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAppointmentLabel">Edit Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Service Name<span class="required">*</span></label>
                                        <input type="text" name="Service" class="form-control" placeholder="Parsley"
                                            required="">
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


@push('scripts')

@endpush