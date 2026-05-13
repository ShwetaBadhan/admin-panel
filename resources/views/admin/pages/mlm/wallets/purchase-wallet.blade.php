@extends('admin.layout.admin-master')
@section('title', 'Purchase Wallet')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-4">
            <h3 class="mb-1">Purchase Wallet</h3>
            <p class="text-muted mb-0">Track package purchases, activations, and pending payments.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar-lg bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-shopping-cart text-primary fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Total Purchases</p>
                            <h4 class="mb-0">₹{{ number_format($totalPurchases, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar-lg bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Active Packages</p>
                            <h4 class="mb-0 text-success">{{ $activePurchases }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar-lg bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-hourglass-half text-warning fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Pending Payments</p>
                            <h4 class="mb-0 text-warning">₹{{ number_format($pendingPayments, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Purchases Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Recent Purchases</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>User / Package</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Mode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPurchases as $purchase)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <strong>{{ $purchase->user_name ?? $purchase->mlm_user_id ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $purchase->package_name ?? $purchase->plan ?? 'Package' }}</small>
                                    </td>
                                    <td class="fw-bold">₹{{ number_format($purchase->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $purchase->status === 'active' ? 'success' : ($purchase->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($purchase->status ?? 'unknown') }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($purchase->payment_mode ?? $purchase->method ?? 'N/A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No purchase records found yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection