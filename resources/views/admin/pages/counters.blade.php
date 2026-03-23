@extends("admin.layout.admin-master")
@section("title", "Counter Section | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Counter Section</li>
                </ol>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Counter Section</h4>
                </div>
                <div class="card-body">
                    <!-- SweetAlert Success Message -->
                    @if(session('success'))
                        <script>
                            Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', timer: 4000 });
                        </script>
                    @endif

                    <form action="{{ route('counters.update') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row g-4">
                            <!-- Counter 1 -->
                            <div class="col-lg-6">
                                <label class="form-label">Title 1</label>
                                <input type="text" name="title1" value="{{ old('title1', $counter->title1) }}"
                                    class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Count 1</label>
                                <input type="number" name="count1" value="{{ old('count1', $counter->count1) }}"
                                    class="form-control" required min="0">
                            </div>

                            <!-- Counter 2 -->
                            <div class="col-lg-6">
                                <label class="form-label">Title 2</label>
                                <input type="text" name="title2" value="{{ old('title2', $counter->title2) }}"
                                    class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Count 2</label>
                                <input type="number" name="count2" value="{{ old('count2', $counter->count2) }}"
                                    class="form-control" required min="0">
                            </div>

                            <!-- Counter 3 -->
                            <div class="col-lg-6">
                                <label class="form-label">Title 3</label>
                                <input type="text" name="title3" value="{{ old('title3', $counter->title3) }}"
                                    class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Count 3</label>
                                <input type="number" name="count3" value="{{ old('count3', $counter->count3) }}"
                                    class="form-control" required min="0">
                            </div>

                            <!-- Counter 4 -->
                            <div class="col-lg-6">
                                <label class="form-label">Title 4</label>
                                <input type="text" name="title4" value="{{ old('title4', $counter->title4) }}"
                                    class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Count 4</label>
                                <input type="number" name="count4" value="{{ old('count4', $counter->count4) }}"
                                    class="form-control" required min="0">
                            </div>
                        </div>

                        <div class="mt-5 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Update All Counters
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection