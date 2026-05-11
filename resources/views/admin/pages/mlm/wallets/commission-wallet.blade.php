@extends('admin.layout.admin-master')
@section('title', 'Commission Wallet')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-titles mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Commission Wallet</h3>
                    <p class="text-muted mb-0">Manage Commission Wallet</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Wallet Selector -->
                    <select class="form-select form-select-sm" style="width: 180px;" onchange="updateFilter('wallet', this.value)">
                        <option value="commission" {{ $walletFilter == 'commission' ? 'selected' : '' }}>Commission Wallet</option>
                        <option value="purchase" {{ $walletFilter == 'purchase' ? 'selected' : '' }}>Purchase Wallet</option>
                        <option value="reward" {{ $walletFilter == 'reward' ? 'selected' : '' }}>Reward Wallet</option>
                    </select>
                    
                    <!-- Period Filter -->
                    <select class="form-select form-select-sm" style="width: 140px;" onchange="updateFilter('period', this.value)">
                        <option value="all" {{ $period == 'all' ? 'selected' : '' }}> Period</option>
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                    
                    <!-- Transaction Type -->
                    <select class="form-select form-select-sm" style="width: 160px;" onchange="updateFilter('type', this.value)">
                        <option value="all" {{ $typeFilter == 'all' ? 'selected' : '' }}>All Transactions</option>
                        <option value="direct_income" {{ $typeFilter == 'direct_income' ? 'selected' : '' }}>Direct Income</option>
                        <option value="matching_income" {{ $typeFilter == 'matching_income' ? 'selected' : '' }}>Binary Income</option>
                        <option value="bonus" {{ $typeFilter == 'bonus' ? 'selected' : '' }}>Bonus</option>
                        <option value="commission" {{ $typeFilter == 'commission' ? 'selected' : '' }}>Commission</option>
                        <option value="adjustment" {{ $typeFilter == 'adjustment' ? 'selected' : '' }}>Admin Adjustment</option>
                    </select>
                    
                    <!-- Status Filter -->
                    <select class="form-select form-select-sm" style="width: 130px;" onchange="updateFilter('status', this.value)">
                        <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="completed" {{ $statusFilter == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ $statusFilter == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Available Balance -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Available Balance</p>
                        <h3 class="mb-0">₹{{ number_format($availableBalance, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Pending Commissions -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Pending Commissions</p>
                        <h3 class="mb-0 text-warning">₹{{ number_format($pendingCommissions, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Total Earnings</p>
                        <h3 class="mb-0 text-success">₹{{ number_format($totalEarnings, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Withdrawal -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Total Withdrawal</p>
                        <h3 class="mb-0 text-info">₹{{ number_format($totalWithdrawal, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Category</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Associated User</th>
                                <th class="pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                                <tr>
                                    <td class="ps-4">
                                        @php
                                            $categoryMap = [
                                                'direct_income' => ['label' => 'Direct Income', 'icon' => 'fa-user-plus', 'color' => 'primary'],
                                                'matching_income' => ['label' => 'Binary Income', 'icon' => 'fa-sitemap', 'color' => 'success'],
                                                'bonus' => ['label' => 'Bonus', 'icon' => 'fa-gift', 'color' => 'warning'],
                                                'commission' => ['label' => 'Commission', 'icon' => 'fa-percent', 'color' => 'info'],
                                                'adjustment' => ['label' => 'Admin Adjustment', 'icon' => 'fa-tools', 'color' => 'secondary'],
                                                'withdrawal' => ['label' => 'Withdrawal', 'icon' => 'fa-arrow-down', 'color' => 'danger'],
                                            ];
                                            $category = $categoryMap[$txn->type] ?? ['label' => $txn->type, 'icon' => 'fa-circle', 'color' => 'secondary'];
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="bg-{{ $category['color'] }} bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas {{ $category['icon'] }} text-{{ $category['color'] }}"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $category['label'] }}</strong>
                                                <small class="d-block text-muted">{{ $txn->description }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">₹{{ number_format($txn->currency_amount, 2) }}</strong>
                                        @if($txn->cc_amount > 0)
                                            <br><small class="text-muted">{{ $txn->cc_amount }} CC</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($txn->status) {
                                                'completed' => 'bg-success',
                                                'pending' => 'bg-warning text-dark',
                                                'failed' => 'bg-danger',
                                                'cancelled' => 'bg-secondary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} bg-opacity-10 text-{{ $txn->status === 'completed' ? 'success' : ($txn->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($txn->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $txn->created_at->format('d M Y') }}</strong>
                                            <br><small class="text-muted">{{ $txn->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($txn->meta && isset($txn->meta['associated_user']))
                                            <div>
                                                <strong>{{ $txn->meta['associated_user'] }}</strong>
                                                @if(isset($txn->meta['associated_username']))
                                                    <br><small class="text-muted">@{{ $txn->meta['associated_username'] }}</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="pe-4">
                                        <button class="btn btn-sm btn-light" onclick="viewTransaction({{ $txn->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        <h5>No transactions found</h5>
                                        <p class="mb-0">Try adjusting your filters or check back later.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="p-3 border-top">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div class="modal fade" id="txnDetailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="txnDetailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 text-muted">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Filter update function
function updateFilter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.location.href = url.toString();
}

// View transaction details
function viewTransaction(id) {
    const modal = document.getElementById('txnDetailModal');
    const content = document.getElementById('txnDetailContent');
    const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
    
    content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading...</p></div>';
    bsModal.show();
    
    // Simple fetch - you can add AJAX endpoint later
    fetch(`/api/transactions/${id}`)
        .then(res => res.json())
        .then(data => {
            content.innerHTML = `
                <div class="text-center mb-4">
                    <div class="bg-light rounded p-4 d-inline-block">
                        <i class="fas fa-exchange-alt fa-3x text-primary mb-2"></i>
                        <h4 class="mb-0">₹${data.currency_amount}</h4>
                        <small class="text-muted">${data.type}</small>
                    </div>
                </div>
                <table class="table table-sm">
                    <tr><td class="text-muted">Date</td><td><strong>${data.created_at}</strong></td></tr>
                    <tr><td class="text-muted">Status</td><td><span class="badge bg-success">${data.status}</span></td></tr>
                    <tr><td class="text-muted">CC Points</td><td>${data.cc_amount}</td></tr>
                    <tr><td class="text-muted">Description</td><td>${data.description || '—'}</td></tr>
                </table>
            `;
        })
        .catch(() => {
            content.innerHTML = '<div class="alert alert-info">Transaction details will be available soon.</div>';
        });
}
</script>
@endpush
@endsection