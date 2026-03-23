@extends("admin.layout.admin-master")
@section("title", "Theme Settings | Continuity Care")
@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-titles">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Theme Settings</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Settings</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @include("admin.components.setting-sidebar")
            <div class="col-lg-9 ps-0">
                <div class="container mt-0">

                    <!-- row -->
                    <div class="row">
                        <div class="col-xl-12 col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Theme Settings</h4>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-6 mb-3">
                                                <div class="example">
                                                    <p class="mb-1">Simple mode</p>
                                                    <input type="text" class="as_colorpicker form-control"
                                                        value="#7ab2fa" />
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-6 mb-3">
                                                <div class="example">
                                                    <p class="mb-1">Complex mode</p>
                                                    <input type="text" class="complex-colorpicker form-control"
                                                        value="#fa7a7a" />
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-6 mb-3">
                                                <div class="example">
                                                    <p class="mb-1">Gradiant mode</p>
                                                    <input type="text" class="gradient-colorpicker form-control"
                                                        value="#bee0ab" />
                                                </div>
                                            </div>


                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection