@extends("admin.layout.admin-master")
@section("title", "Popular Test | Continuity Care")
@section("content")
	<div class="content-body">
		<!-- row -->
		<div class="container-fluid">
			<div class="page-titles">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
					<li class="breadcrumb-item  active"><a href="javascript:void(0)">Popular Test</a></li>
				</ol>
			</div>

			<div class="form-head d-flex mb-3 mb-md-4 align-items-start">
				<div class="input-group search-area me-auto d-inline-flex ">
					<input type="text" class="form-control" placeholder="Search here">
					<div class="input-group-append">
						<button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
					</div>
				</div>
				<div class="ms-auto d-lg-block">
					<a href="javascript:void(0);" class="btn btn-primary btn-rounded" data-bs-toggle="modal"
						data-bs-target="#addAppointment">+ Add New</a>
					<a href="javascript:void(0);" class="btn btn-danger btn-rounded deleteSelected">Delete Selected</a>

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
									<th>Test Title</th>
									<th>Price</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($PopularTest as $test)

									<tr>
										<td>
											<div class="checkbox text-end align-self-center">
												<div class="form-check custom-checkbox ">
													<input type="checkbox" class="form-check-input checkItem"
														value="{{ $test->id }}" required="">
													<label class="form-check-label" for="checkbox"></label>
												</div>
											</div>
										</td>
										<td>
											<img alt="" src="{{ asset('storage/' . $test->image) }}" height="43" width="43"
												class="rounded-circle ms-4">
										</td>


										<td>{{ $test->title }}
										</td>

										<td>
											<div class="d-flex align-items-center">
												@if($test->status == 'Active')
													<span class="text-success font-w600">Active</span>
												@else
													<span class="text-danger font-w600">Inactive</span>
												@endif
											</div>
										</td>

										<td>
											<span class="me-3">
												<a href="javascript:void(0);" class="viewDoctor btn btn-sm btn-info light"
													data-id="{{ $test->id }}">
													<i class="fa fa-eye fs-18"></i>
												</a>
											</span>
											<span class="me-3">
												<a href="javascript:void(0)" class="editDoctor btn btn-sm btn-warning light"
													data-id="{{ $test->id }}"><i class="fa fa-pencil fs-18 "></i></a>
											</span>
											<span>
												<a href="javascript:void(0);" class="deleteDoctor btn btn-sm btn-danger light"
													data-id="{{ $test->id }}">
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
	{{-- ADD MODAL --}}
	<div class="modal fade" id="addAppointment" tabindex="-1" aria-labelledby="addAppointment" aria-hidden="true">
		<div class="modal-dialog custom-modal" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addAppointmentLabel">Add Popular Test Details</h5>
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
					@if($errors->any())
						<div class="alert alert-danger">
							<ul class="mb-0">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<form action="{{ url('/popularTest/store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
									<label class="col-form-label">Test Title:</label>
									<input type="text" name="title" class="form-control" id="name1"
										placeholder="Title of Test">
								</div>
							</div>
							<div class="col-xl-6">
								<label>Image</label><br>
								<small>Only png | jpeg | jpg files allowed.</small>
								<input type="file" name="image" class="form-control">
							</div>

							<div class="col-xl-12">
								<div class="form-group">
									<label class="col-form-label">Description:</label>
									<textarea name="description" class="form-control" id="add_description"
										placeholder="This is about Test"></textarea>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<label class="col-form-label">Overview:</label>
									<textarea name="overview" class="form-control" id="add_overview"
										placeholder="This is overview of Test"></textarea>
								</div>
							</div>
							<div class="col-xl-6">
								<div class="">
									<label class="col-form-label">Price:</label>
									<input type="text" name="price" class="form-control" id="" placeholder="1200.00">
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label class="col-form-label">Status:</label>
									<select class="form-control" name="status">
										<option value="Active">Active</option>
										<option value="Inactive">Inactive</option>
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

	{{-- VIEW MODAL --}}
	<div class="modal fade" id="viewAppointment" tabindex="-1">
		<div class="modal-dialog custom-modal modal-centered">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title">View Popular Test</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<table class="table table-bordered table-striped mb-0">
					<tr>
						<th>Title :</th>
						<td id="v_title"></td>

						<th>Image :</th>
						<td>
							<img id="v_image" src="" width="80" class="rounded">
						</td>
					</tr>

					<tr>
						<th>Status :</th>
						<td id="v_status"></td>

						<th>Description :</th>
						<td id="v_description"></td>
					</tr>

					<tr>
						<th>Overview :</th>
						<td id="v_overview"></td>

						<th>Price :</th>
						<td id="v_price"></td>
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
		<div class="modal-dialog custom-modal modal-centered">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title">Edit Popular Test Details </h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<form id="editDoctorForm" method="POST" enctype="multipart/form-data">

					@csrf
					<input type="hidden" id="edit_id" name="id">

					<div class="modal-body">
						<div class="row">

							<div class="col-xl-6">
								<label>Title</label>
								<input type="text" id="edit_title" name="title" class="form-control">
							</div>

							<div class="col-xl-6">
								<label>Image</label>
								<input type="file" id="edit_image" name="image" class="form-control">
								<img id="edit_preview" src="" width="70" class="mt-2 rounded">
							</div>

							<div class="col-xl-6">
								<label>Status</label>
								<select id="edit_status" name="status" class="form-control">
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							</div>
							<div class="col-xl-6">
								<label>Price</label>
								<input type="number" id="edit_price" name="price" class="form-control">
							</div>
							<div class="col-xl-12">
								<label>Description</label>
								<textarea name="description" id="edit_description" class="form-control"></textarea>
							</div>
							<div class="col-xl-12">
								<label>Overview</label>
								<textarea name="overview" id="edit_overview" class="form-control"></textarea>
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
@push('scripts')
	<script>
		// Sync Summernote content to the underlying textarea BEFORE form submits
		$('#addAppointment form').on('submit', function () {
			// Get HTML content from Summernote and set it to the textarea
			$('textarea#add_description').val($('#add_description').summernote('code'));
			$('textarea#add_overview').val($('#add_overview').summernote('code'));
		});


		$(function () {
			$('#add_description, #add_overview').summernote({
				placeholder: 'Write here...',
				tabsize: 2,
				height: 100
			});

			$('#edit_description, #edit_overview').summernote({
				placeholder: 'Write here...',
				tabsize: 2,
				height: 200
			});
		});
		$(document).on('click', '.viewDoctor', function () {

			var id = $(this).data('id');

			$.ajax({
				url: "{{ url('/popularTest/view') }}/" + id,
				type: "GET",
				success: function (PopularTest) {

					// Fill modal data
					$('#v_title').text(PopularTest.title);
					$('#v_status').text(PopularTest.status == 'Active' ? 'Active' : 'Inactive');
					$('#v_description').html(PopularTest.description);
					$('#v_overview').html(PopularTest.overview);

					// Image
					$('#v_image').attr('src', '/storage/' + PopularTest.image);

					// If you have appointment count
					$('#v_price').text(PopularTest.price);

					// Open modal
					$('#viewAppointment').modal('show');
				}
			});

		});
		$(document).on('click', '.editDoctor', function () {

			var id = $(this).data('id');

			$.ajax({
				url: "{{ url('/popularTest/view') }}/" + id,
				type: "GET",
				success: function (popularTest) {

					$('#edit_id').val(popularTest.id);
					$('#edit_title').val(popularTest.title);
					$('#edit_status').val(popularTest.status);
					$('#edit_price').val(popularTest.price);
					$('#edit_description').summernote('code', popularTest.description || '');
					$('#edit_overview').summernote('code', popularTest.overview || '');


					$('#edit_preview').attr('src', '/storage/' + popularTest.image);

					$('#editAppointment').modal('show');
				}
			});
		});
		$('#editDoctorForm').on('submit', function (e) {
			e.preventDefault();

			$('#edit_description').val($('#edit_description').summernote('code'));
			$('#edit_overview').val($('#edit_overview').summernote('code'));

			let formData = new FormData(this);

			$.ajax({
				type: "POST",
				url: "{{ url('/popularTest/update') }}",
				data: formData,
				contentType: false,
				processData: false,

				success: function (response) {
					Swal.fire("Updated!", "Test updated successfully!", "success");
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
				text: "This Popular Test will be permanently deleted!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: "Yes, delete it!"
			}).then((result) => {

				if (result.isConfirmed) {

					$.ajax({
						url: "{{ url('/popularTest/delete') }}/" + id,
						type: "DELETE",
						data: {
							_token: "{{ csrf_token() }}"
						},
						success: function (response) {

							Swal.fire("Deleted!", "Popular Test removed successfully.", "success");

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
					Swal.fire("Oops!", "Please select at least one Popular Test.", "warning");
					return;
				}

				Swal.fire({
					title: "Are you sure?",
					text: "Selected Popular Test will be deleted permanently!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#d33",
					cancelButtonColor: "#3085d6",
					confirmButtonText: "Yes, delete selected!"
				}).then((result) => {

					if (result.isConfirmed) {

						$.ajax({
							url: "/popularTest/delete-selected",

							type: "POST",
							data: {
								ids: selected,
								_token: "{{ csrf_token() }}"
							},
							success: function (response) {
								Swal.fire("Deleted!", "Selected Popular Test removed.", "success");

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




	</script>
@endpush