@extends('admin.layout.admin-master')
@section('title', 'Account Activated')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-6">
                <div class="card shadow text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h3 class="mt-4">Account Activated! 🎉</h3>
                        <p>Welcome, <strong>{{ $userName }}</strong>!</p>
                        <p>Your MLM account is now active.</p>
                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle me-2"></i>
                            You can now login to your account.
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary mt-3">Login Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection