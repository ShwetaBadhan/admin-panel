@extends('admin.layout.admin-master')
@section('title', 'CC Logs')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-titles mb-4">
            <div>
                <h3 class="mb-1">CC Logs</h3>
                <p class="text-muted mb-0">Manage CC Logs</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex gap-2">
                    <!-- Source Filter -->
                    <select class="form-select form-select-sm" style="width: 200px;" onchange="updateFilter('source', this.value)">
                        <option value="all" {{ $sourceFilter == 'all' ? 'selected' : '' }}>All Sources</option>
                        <option value="direct_income" {{ $sourceFilter == 'direct_income' ? 'selected' : '' }}>Direct Income</option>
                        <option value="matching_income" {{ $sourceFilter == 'matching_income' ? 'selected' : '' }}>Binary Income</option>
                        <option value="bonus" {{ $sourceFilter == 'bonus' ? 'selected' : '' }}>Bonus</option>
                        <option value="commission" {{ $sourceFilter == 'commission' ? 'selected' : '' }}>Commission</option>
                        <option value="order" {{ $sourceFilter == 'order' ? 'selected' : '' }}>Order</option>
                        <option value="adjustment" {{ $sourceFilter == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                    </select>
                    
                    <!-- Period Filter -->
                    <select class="form-select form-select-sm" style="width: 160px;" onchange="updateFilter('period', this.value)">
                        <option value="all" {{ $period == 'all' ? 'selected' : '' }}>📅 Period</option>
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CC Logs Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Source</th>
                                <th>CC Amount</th>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ccLogs as $log)
                                <tr>
                                    <td class="ps-4">
                                        @php
                                            $sourceMap = [
                                                'direct_income' => ['label' => 'Direct Income', 'badge' => 'primary'],
                                                'matching_income' => ['label' => 'Binary Income', 'badge' => 'success'],
                                                'bonus' => ['label' => 'Bonus', 'badge' => 'warning'],
                                                'commission' => ['label' => 'Commission', 'badge' => 'info'],
                                                'order' => ['label' => 'Order', 'badge' => 'secondary'],
                                                'adjustment' => ['label' => 'Adjustment', 'badge' => 'dark'],
                                            ];
                                            $source = $sourceMap[$log->type] ?? ['label' => ucfirst($log->type), 'badge' => 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $source['badge'] }} bg-opacity-10 text-{{ $source['badge'] }}">
                                            {{ $source['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($log->cc_amount, 2) }}</strong>
                                        <small class="text-muted d-block">CC</small>
                                    </td>
                                    <td>
                                        <strong>{{ $log->user->user_name }}</strong>
                                    </td>
                                    <td>
                                        {{ $log->user->first_name }} {{ $log->user->last_name }}
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $log->created_at->format('d M Y') }}</strong>
                                            <br><small class="text-muted">{{ $log->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                            <h5 class="mb-1">No CC Logs Found</h5>
                                            <p class="mb-0 small">Your CC transaction history will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($ccLogs->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <label class="text-muted small me-2">Rows per page:</label>
                            <select class="form-select form-select-sm d-inline-block" style="width: 80px;" onchange="updatePerPage(this.value)">
                                <option value="15" {{ $ccLogs->perPage() == 15 ? 'selected' : '' }}>15</option>
                                <option value="30" {{ $ccLogs->perPage() == 30 ? 'selected' : '' }}>30</option>
                                <option value="50" {{ $ccLogs->perPage() == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div>
                            {{ $ccLogs->links() }}
                        </div>
                    </div>
                @else
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <label class="text-muted small me-2">Rows per page:</label>
                            <select class="form-select form-select-sm d-inline-block" style="width: 80px;" onchange="updatePerPage(this.value)">
                                <option value="15" selected>15</option>
                                <option value="30">30</option>
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


@endsection

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