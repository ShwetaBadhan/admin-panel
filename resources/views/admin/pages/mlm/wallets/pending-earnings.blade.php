@extends('admin.layout.admin-master')
@section('title', 'Pending Earnings')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-titles mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Pending Earning</h3>
                    <p class="text-muted mb-0">Manage Pending Earning</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Wallet Filter -->
                    <select class="form-select form-select-sm" style="width: 160px;" onchange="updateFilter('wallet', this.value)">
                        <option value="all" {{ $walletFilter == 'all' ? 'selected' : '' }}>🏦 All Wallet</option>
                        <option value="commission" {{ $walletFilter == 'commission' ? 'selected' : '' }}>Commission Wallet</option>
                        <option value="purchase" {{ $walletFilter == 'purchase' ? 'selected' : '' }}>Purchase Wallet</option>
                        <option value="reward" {{ $walletFilter == 'reward' ? 'selected' : '' }}>Reward Wallet</option>
                    </select>
                    
                    <!-- Period Filter -->
                    <select class="form-select form-select-sm" style="width: 140px;" onchange="updateFilter('period', this.value)">
                        <option value="all" {{ $period == 'all' ? 'selected' : '' }}>📅 Period</option>
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pending Earnings Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Wallet</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingEarnings as $earning)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $earning->user->first_name }} {{ $earning->user->last_name }}</strong>
                                                <br><small class="text-muted">@{{ $earning->user->user_name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $walletMap = [
                                                'direct_income' => ['label' => 'Commission', 'badge' => 'primary'],
                                                'matching_income' => ['label' => 'Binary', 'badge' => 'success'],
                                                'bonus' => ['label' => 'Bonus', 'badge' => 'warning'],
                                                'commission' => ['label' => 'Commission', 'badge' => 'info'],
                                                'adjustment' => ['label' => 'Adjustment', 'badge' => 'secondary'],
                                            ];
                                            $wallet = $walletMap[$earning->type] ?? ['label' => 'Other', 'badge' => 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $wallet['badge'] }} bg-opacity-10 text-{{ $wallet['badge'] }}">
                                            {{ $wallet['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ ucfirst(str_replace('_', ' ', $earning->type)) }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($earning->description, 30) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">₹{{ number_format($earning->currency_amount, 2) }}</strong>
                                        @if($earning->cc_amount > 0)
                                            <br><small class="text-muted">{{ $earning->cc_amount }} CC</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $earning->created_at->format('d M Y') }}</strong>
                                            <br><small class="text-muted">{{ $earning->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                            <h5 class="mb-1">No Pending Earning Found</h5>
                                            <p class="mb-0 small">All your earnings have been credited</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($pendingEarnings->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <label class="text-muted small me-2">Rows per page:</label>
                            <select class="form-select form-select-sm d-inline-block" style="width: 80px;" onchange="updatePerPage(this.value)">
                                <option value="10" {{ $pendingEarnings->perPage() == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $pendingEarnings->perPage() == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $pendingEarnings->perPage() == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div>
                            {{ $pendingEarnings->links() }}
                        </div>
                    </div>
                @else
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <label class="text-muted small me-2">Rows per page:</label>
                            <select class="form-select form-select-sm d-inline-block" style="width: 80px;" onchange="updatePerPage(this.value)">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="text-muted small">
                            <span>Page 1 of 0</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Update filter
function updateFilter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.location.href = url.toString();
}

// Update rows per page
function updatePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}
</script>
@endpush
@endsection