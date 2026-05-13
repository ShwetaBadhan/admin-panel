@extends('admin.layout.admin-master')
@section('title', 'Commission Wallet')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-titles mb-4">
            <h3 class="mb-1">Commission Wallet</h3>
            <p class="text-muted mb-0">Track your commission earnings and withdrawals.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Commission -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                <i class="fas fa-coins text-primary fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-1">Total Commission</p>
                                <h4 class="mb-0">₹{{ number_format($totalCommission, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Balance -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                <i class="fas fa-wallet text-success fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-1">Available Balance</p>
                                <h4 class="mb-0 text-success">₹{{ number_format($availableBalance, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Commission -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                                <i class="fas fa-clock text-warning fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-1">Pending Commission</p>
                                <h4 class="mb-0 text-warning">₹{{ number_format($pendingCommission, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdrawn Amount -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg bg-info bg-opacity-10 rounded-3 p-3 me-3">
                                <i class="fas fa-hand-holding-usd text-info fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-1">Withdrawn</p>
                                <h4 class="mb-0 text-info">₹{{ number_format($withdrawnAmount, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today & Weekly Earnings -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-sun text-warning fa-2x mb-2"></i>
                        <p class="text-muted mb-1">Today's Earnings</p>
                        <h3 class="mb-0">₹{{ number_format($todayEarnings, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-week text-primary fa-2x mb-2"></i>
                        <p class="text-muted mb-1">This Week</p>
                        <h3 class="mb-0">₹{{ number_format($weeklyEarnings, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Recent Commission Transactions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $txn)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($txn->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $txn->type === 'commission' ? 'primary' : 'info' }}">
                                            {{ ucfirst(str_replace('_', ' ', $txn->type)) }}
                                        </span>
                                    </td>
                                    <td class="text-success">+₹{{ number_format($txn->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $txn->status === 'credited' ? 'success' : 'warning' }}">
                                            {{ ucfirst($txn->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>{{ $txn->description ?? 'Commission payout' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No commission transactions yet.
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