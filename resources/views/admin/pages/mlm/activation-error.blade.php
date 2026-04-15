@extends('admin.layout.admin-master')
@section('title', 'Activation Error')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-6">
                <div class="card shadow text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                        <h3 class="mt-4">Activation Failed</h3>
                        <p class="text-muted">{{ $message ?? 'The activation link is invalid or has expired.' }}</p>
                        <a href="{{ route('login') }}" class="btn btn-secondary mt-3">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection