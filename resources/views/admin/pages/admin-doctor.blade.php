@extends("admin.layout.admin-master")
@section("title", "Our Staff | Continuity Care")
@section("content")
	<div class="content-body">
		<!-- row -->
		<div class="container-fluid">
			<div class="page-titles">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
					<li class="breadcrumb-item  active"><a href="javascript:void(0)">Our Staff</a></li>
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
									<th>Image</th>
									<th>Staff Name</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($doctors as $doctor)

									<tr>
										<td>
											<div class="checkbox text-end align-self-center">
												<div class="form-check custom-checkbox ">
													<input type="checkbox" class="form-check-input checkItem"
														value="{{ $doctor->id }}" required="">
													<label class="form-check-label" for="checkbox"></label>
												</div>
											</div>
										</td>
										<td>
											<img alt="" src="{{ asset('uploads/' . $doctor->image) }}" height="43" width="43"
												class="rounded-circle ms-4">
										</td>


										<td>{{ $doctor->fullname }}
										</td>

										<td>
											<div class="d-flex align-items-center">
												@if($doctor->status == 1)
													<span class="text-success font-w600">Available</span>
												@else
													<span class="text-danger font-w600">Unavailable</span>
												@endif
											</div>
										</td>

										<td>
											<span class="me-3">
												<a href="javascript:void(0);" class="viewDoctor btn btn-sm btn-info light"
													data-id="{{ $doctor->id }}">
													<i class="fa fa-eye fs-18"></i>
												</a>
											</span>
											<span class="me-3">
												<a href="javascript:void(0)" class="editDoctor btn btn-sm btn-warning light"
													data-id="{{ $doctor->id }}"><i class="fa fa-pencil fs-18 "></i></a>
											</span>
											<span>
												<a href="javascript:void(0);" class="deleteDoctor btn btn-sm btn-danger light"
													data-id="{{ $doctor->id }}">
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
	{{-- ADD DOCTOR MODAL --}}
	<div class="modal fade" id="addAppointment" tabindex="-1" aria-labelledby="addAppointment" aria-hidden="true">
		<div class="modal-dialog custom-modal" role="document">
			<div class="modal-content">
				<div class="modal-header bg-theme-light">
					<h5 class="modal-title" id="addAppointmentLabel">Add Staff Details</h5>
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
					<form action="{{ url('/doctors/store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
									<label class="col-form-label">Name:</label>
									<input type="text" name="fullname" class="form-control" id="name1" placeholder="Name">
								</div>
							</div>
							<div class="col-xl-6">
								<label>Image</label><br>
								<small>Only png | jpeg | jpg files allowed.</small>
								<input type="file" name="image" class="form-control">
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label class="col-form-label">Status:</label>
									<select class="form-control" name="status">
										<option value="1">Available</option>
										<option value="0">Unavailable</option>
									</select>
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label class="col-form-label">Designation:</label>
									<input type="text" name="designation" class="form-control" id="" placeholder="Dentist">
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label class="col-form-label">Department :</label>
									<input type="text" name="specialization" class="form-control" id=""
										placeholder="Dentist">
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
		<div class="modal-dialog custom-modal">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title">View Doctor</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<table class="table table-bordered table-striped mb-0">
					<tr>
						<th>Name :</th>
						<td id="v_name"></td>

						<th>Image :</th>
						<td>
							<img id="v_image" src="" width="80" class="rounded">
						</td>
					</tr>

					<tr>
						<th>Status :</th>
						<td id="v_status"></td>

						<th>Designation :</th>
						<td id="v_designation"></td>
					</tr>

					<tr>
						<th>Specialization :</th>
						<td id="v_specialization"></td>

						<th>No. of Appointments :</th>
						<td id="v_appointments">0</td>
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
		<div class="modal-dialog custom-modal">
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
								<label>Name</label>
								<input type="text" id="edit_fullname" name="fullname" class="form-control">
							</div>

							<div class="col-xl-6">
								<label>Image</label>
								<input type="file" id="edit_image" name="image" class="form-control">
								<img id="edit_preview" src="" width="70" class="mt-2 rounded">
							</div>

							<div class="col-xl-6">
								<label>Status</label>
								<select id="edit_status" name="status" class="form-control">
									<option value="1">Available</option>
									<option value="0">Unavailable</option>
								</select>
							</div>

							<div class="col-xl-6">
								<label>Designation</label>
								<input type="text" id="edit_designation" name="designation" class="form-control">
							</div>

							<div class="col-xl-6">
								<label>Specialization</label>
								<input type="text" id="edit_specialization" name="specialization" class="form-control">
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
		$(document).on('click', '.viewDoctor', function () {

			var id = $(this).data('id');

			$.ajax({
				url: "{{ url('/doctors/view') }}/" + id,
				type: "GET",
				success: function (doctor) {

					// Fill modal data
					$('#v_name').text(doctor.fullname);
					$('#v_status').text(doctor.status == 1 ? 'Available' : 'Unavailable');
					$('#v_designation').text(doctor.designation);
					$('#v_specialization').text(doctor.specialization);

					// Image
					$('#v_image').attr('src', '/uploads/' + doctor.image);

					// If you have appointment count
					$('#v_appointments').text(doctor.appointments ?? 0);

					// Open modal
					$('#viewAppointment').modal('show');
				}
			});

		});
		$(document).on('click', '.editDoctor', function () {

			var id = $(this).data('id');

			$.ajax({
				url: "{{ url('/doctors/view') }}/" + id,
				type: "GET",
				success: function (doctor) {

					$('#edit_id').val(doctor.id);
					$('#edit_fullname').val(doctor.fullname);
					$('#edit_status').val(doctor.status);
					$('#edit_designation').val(doctor.designation);
					$('#edit_specialization').val(doctor.specialization);

					$('#edit_preview').attr('src', '/uploads/' + doctor.image);

					$('#editAppointment').modal('show');
				}
			});
		});
		$('#editDoctorForm').on('submit', function (e) {
			e.preventDefault();

			let formData = new FormData(this);

			$.ajax({
				type: "POST",
				url: "{{ url('/doctors/update') }}",
				data: formData,
				contentType: false,
				processData: false,

				success: function (response) {
					Swal.fire("Updated!", "Doctor updated successfully!", "success");
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
				text: "This doctor will be permanently deleted!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: "Yes, delete it!"
			}).then((result) => {

				if (result.isConfirmed) {

					$.ajax({
						url: "{{ url('/doctors/delete') }}/" + id,
						type: "DELETE",
						data: {
							_token: "{{ csrf_token() }}"
						},
						success: function (response) {

							Swal.fire("Deleted!", "Doctor removed successfully.", "success");

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
					Swal.fire("Oops!", "Please select at least one doctors.", "warning");
					return;
				}

				Swal.fire({
					title: "Are you sure?",
					text: "Selected doctor will be deleted permanently!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#d33",
					cancelButtonColor: "#3085d6",
					confirmButtonText: "Yes, delete selected!"
				}).then((result) => {

					if (result.isConfirmed) {

						$.ajax({
							url: "/doctors/delete-selected",

							type: "POST",
							data: {
								ids: selected,
								_token: "{{ csrf_token() }}"
							},
							success: function (response) {
								Swal.fire("Deleted!", "Selected doctor removed.", "success");

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