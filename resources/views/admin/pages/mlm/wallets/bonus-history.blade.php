@extends('admin.layout.admin-master')
@section('title', 'Bonus History')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-titles mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Bonus History</h3>
                    <p class="text-muted mb-0">Manage Bonus History</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- All Rewards Filter -->
                    <select class="form-select form-select-sm" style="width: 180px;" onchange="updateFilter('reward', this.value)">
                        <option value="all" {{ $rewardFilter == 'all' ? 'selected' : '' }}>🎁 All Rewards</option>
                        <option value="direct_income" {{ $rewardFilter == 'direct_income' ? 'selected' : '' }}>Direct Income</option>
                        <option value="matching_income" {{ $rewardFilter == 'matching_income' ? 'selected' : '' }}>Binary Income</option>
                        <option value="bonus" {{ $rewardFilter == 'bonus' ? 'selected' : '' }}>Bonus</option>
                        <option value="commission" {{ $rewardFilter == 'commission' ? 'selected' : '' }}>Commission</option>
                        <option value="adjustment" {{ $rewardFilter == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
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

        <!-- Bonus History Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Category</th>
                                <th>User</th>
                                <th>CC Points</th>
                                <th>Amount</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bonuses as $bonus)
                                <tr>
                                    <td class="ps-4">
                                        @php
                                            $categoryMap = [
                                                'direct_income' => ['label' => 'Direct Income', 'icon' => 'fa-user-plus', 'color' => 'primary'],
                                                'matching_income' => ['label' => 'Binary Rp', 'icon' => 'fa-sitemap', 'color' => 'success'],
                                                'bonus' => ['label' => 'Bonus Rp', 'icon' => 'fa-gift', 'color' => 'warning'],
                                                'commission' => ['label' => 'Commission', 'icon' => 'fa-percent', 'color' => 'info'],
                                                'adjustment' => ['label' => 'Adjustment', 'icon' => 'fa-tools', 'color' => 'secondary'],
                                            ];
                                            $category = $categoryMap[$bonus->type] ?? ['label' => $bonus->type, 'icon' => 'fa-circle', 'color' => 'secondary'];
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="bg-{{ $category['color'] }} bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas {{ $category['icon'] }} text-{{ $category['color'] }}"></i>
                                            </div>
                                            <strong>{{ $category['label'] }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $bonus->user->user_name }}</strong>
                                    </td>
                                    <td>
                                        @if($bonus->cc_amount > 0)
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                {{ number_format($bonus->cc_amount, 2) }} CC
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">₹{{ number_format($bonus->currency_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $bonus->created_at->format('d M Y H:i') }}</strong>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                            <h5 class="mb-1">No Bonus Found</h5>
                                            <p class="mb-0 small">Your bonus history will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($bonuses->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <label class="text-muted small me-2">Rows per page:</label>
                            <select class="form-select form-select-sm d-inline-block" style="width: 80px;" onchange="updatePerPage(this.value)">
                                <option value="10" {{ $bonuses->perPage() == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $bonuses->perPage() == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $bonuses->perPage() == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div>
                            {{ $bonuses->links() }}
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
                            <span>Page 1 of 1</span>
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