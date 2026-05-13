@extends('admin.layout.admin-master')
@section('title', 'Pending Earnings')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-4">
            <h3 class="mb-1">Pending Earnings</h3>
            <p class="text-muted mb-0">Review and approve pending commission payouts.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar-lg bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Total Pending Amount</p>
                            <h4 class="mb-0 text-warning">₹{{ number_format($totalPendingAmount, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar-lg bg-info bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-list text-info fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Pending Transactions</p>
                            <h4 class="mb-0 text-info">{{ $pendingCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Earnings Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Pending Earnings List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>User / Reference</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingItems as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <strong>{{ $item->user_name ?? $item->mlm_user_id ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $item->reference_id ?? $item->description ?? 'Earning' }}</small>
                                    </td>
                                    <td>{{ ucfirst($item->type ?? $item->earning_type ?? 'commission') }}</td>
                                    <td class="fw-bold text-warning">₹{{ number_format($item->amount, 2) }}</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1" title="Approve"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Reject"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No pending earnings found.
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