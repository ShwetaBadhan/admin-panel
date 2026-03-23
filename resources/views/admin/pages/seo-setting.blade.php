@extends("admin.layout.admin-master")
@section("title", "SEO Settings | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">

            <!-- Breadcrumb -->
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">SEO Settings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">General</a></li>
                </ol>
            </div>

            <!-- SweetAlert2 Success Message -->
            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ addslashes(session("success")) }}',
                        confirmButtonColor: '#3085d6',
                        timer: 4000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            <div class="row">
                @include("admin.components.setting-sidebar")

                <div class="col-lg-9 ps-0">

                    <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
                        <div class="me-auto d-lg-block">
                            <a href="javascript:void(0);" class="btn btn-primary btn-rounded" data-bs-toggle="modal"
                                data-bs-target="#addAppointment">+ Add New</a>
                        </div>
                        <div class="input-group search-area ms-auto d-inline-flex me-2">
                            <input type="text" class="form-control" placeholder="Search here">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text"><i
                                        class="flaticon-381-search-2"></i></button>
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
                                                        <input type="checkbox" class="form-check-input" id="checkAll"
                                                            required="">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>Page</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($seoSettings as $seo)

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



                                                <td>{{ $seo->title }}
                                                </td>



                                                <td>
                                                    <span class="me-3">
                                                        <a href="javascript:void(0);"
                                                            class="viewSeoSetting btn btn-sm btn-info light"
                                                            data-id="{{ $seo->id }}">
                                                            <i class="fa fa-eye fs-18"></i>
                                                        </a>
                                                    </span>
                                                    <span class="me-3">
                                                        <a href="javascript:void(0)"
                                                            class="editSeoSetting btn btn-sm btn-warning light"
                                                            data-id="{{ $seo->id }}"><i class="fa fa-pencil fs-18 "></i></a>
                                                    </span>
                                                    <span>
                                                        <a href="javascript:void(0);"
                                                            class="deleteSeoSetting btn btn-sm btn-danger light"
                                                            data-id="{{ $seo->id }}">
                                                            <i class="fa fa-trash fs-18 "></i>
                                                        </a>
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
    </div>
    @php
        $pages = \App\Models\SeoPage::where('is_active', '1')->get();
    @endphp

    {{-- ADD DOCTOR MODAL --}}
    <div class="modal fade" id="addAppointment" tabindex="-1" aria-labelledby="addAppointment" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAppointmentLabel">Add SEO</h5>
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
                    <form action="{{ url('/seo-setting/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Title:</label>
                                    <input type="text" name="title" class="form-control" placeholder="Seo Title">
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <label class="form-label">Page</label>
                                <select name="page" class="form-control" style="height:150px;">
                                    @foreach($pages as $page)
                                        <option value="{{ $page->page }}">{{ $page->page }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Description:</label>
                                    <input type="text" name="description" class="form-control" id="description"
                                        placeholder="Seo description">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">Keywords:</label>
                                    <input type="text" name="keywords" class="form-control" id="keywords"
                                        placeholder="Seo Keywords">
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

    {{-- VIEW DOCTOR MODAL --}}
    <div class="modal fade" id="viewAppointment" tabindex="-1">
        <div class="modal-dialog modal-lg modal-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">View </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <table class="table table-bordered table-striped mb-0">
                    <tr>
                        <th>Title :</th>
                        <td id="v_title"></td>
                        <th>Page :</th>
                        <td id="v_page"></td>


                    </tr>

                    <tr>
                        <th>Description :</th>
                        <td colspan="3" id="v_description"></td>



                    </tr>

                    <tr>
                        <th>Keywords :</th>
                        <td colspan="3" id="v_keywords"></td>



                    </tr>


                </table>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    {{-- EDIT DOCTOR MODAL--}}
    <div class="modal fade" id="editAppointment" tabindex="-1">
        <div class="modal-dialog modal-lg modal-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit SEO Details </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editDoctorForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-xl-6">
                                <label>Title</label>
                                <input type="text" id="edit_title" name="title" class="form-control">
                            </div>
                            <div class="col-xl-6">
                                <label>Page</label>
                                <input type="text" id="edit_page" name="page" class="form-control" readonly>
                            </div>
                            {{-- <div class="col-xl-6">
                                <label>Page</label>
                                <select name="page" multiple class="form-control" style="height:150px;">
                                    @foreach($pages as $page)
                                    <option value="{{ $page->page }}" {{ in_array($blog->page, $blog->category_ids ?? []) ?
                                        'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-xl-6">
                                <label>Description</label>
                                <input type="text" id="edit_description" name="description" class="form-control">
                            </div>
                            <div class="col-xl-6">
                                <label>Keywords</label>
                                <input type="text" id="edit_keywords" name="keywords" class="form-control">
                            </div>



                            {{-- <div class="col-xl-6">
                                <label>Status</label>
                                <select id="edit_status" name="status" class="form-control">
                                    <option value="1">Available</option>
                                    <option value="0">Unavailable</option>
                                </select>
                            </div> --}}



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