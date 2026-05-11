@extends('admin.layout.admin-master')
@section('title', 'Pair Matching Logs')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-titles mb-4">
            <div>
                <h3 class="mb-1">Pair Matching Logs</h3>
                <p class="text-muted mb-0">Monitor binary commission pairing history and BV snapshots</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex gap-2">
                    <!-- Status Filter -->
                    <select class="form-select form-select-sm" style="width: 180px;" onchange="updateFilter('status', this.value)">
                        <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="completed" {{ $statusFilter == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
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

        <!-- Pair Matching Logs Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Status</th>
                                <th>Pairs</th>
                                <th>Income</th>
                                <th>CC Before</th>
                                <th>CC After</th>
                                <th>CAP / UNIT</th>
                                <th>Reference</th>
                                <th>Trigger</th>
                                <th>Remarks</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pairLogs as $log)
                                <tr>
                                    <!-- User Info -->
                                    <td class="ps-4">
                                        <div>
                                            <strong>{{ $log->user->user_name }}</strong>
                                            <br><small class="text-muted">ID: {{ $log->user->id }}</small>
                                            <br><small class="text-muted">{{ $log->user->email }}</small>
                                        </div>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td>
                                        @php
                                            $hasPairs = ($log->meta['left_cc'] ?? 0) > 0 || ($log->meta['right_cc'] ?? 0) > 0;
                                        @endphp
                                        @if($hasPairs)
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-check-circle me-1"></i>Matched
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i class="fas fa-times-circle me-1"></i>No Pairs
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Pairs Info -->
                                    <td>
                                        <div class="small">
                                            <strong>Final: {{ $log->meta['matched_cc'] ?? 0 }}</strong><br>
                                            <span class="text-muted">Total: {{ ($log->meta['left_cc'] ?? 0) + ($log->meta['right_cc'] ?? 0) }}</span><br>
                                            <span class="text-muted">LR: {{ $log->meta['left_cc'] ?? 0 }} | SL: {{ $log->meta['right_cc'] ?? 0 }}</span><br>
                                            <span class="text-muted">SR: {{ $log->meta['carry_forward'] ?? 0 }}</span>
                                        </div>
                                    </td>
                                    
                                    <!-- Income -->
                                    <td>
                                        <strong class="text-success">₹{{ number_format($log->currency_amount, 2) }}</strong>
                                    </td>
                                    
                                    <!-- BV Before -->
                                    <td>
                                        <div class="small">
                                            <span class="text-primary">L: {{ $log->meta['left_before'] ?? 0 }}</span><br>
                                            <span class="text-success">R: {{ $log->meta['right_before'] ?? 0 }}</span><br>
                                            <span class="text-muted">S: {{ $log->meta['strong_before'] ?? 0 }}</span>
                                        </div>
                                    </td>
                                    
                                    <!-- BV After -->
                                    <td>
                                        <div class="small">
                                            <span class="text-primary">L: {{ $log->meta['left_after'] ?? 0 }}</span><br>
                                            <span class="text-success">R: {{ $log->meta['right_after'] ?? 0 }}</span><br>
                                            <span class="text-muted">S: {{ $log->meta['strong_after'] ?? 0 }}</span>
                                        </div>
                                    </td>
                                    
                                    <!-- CAP / UNIT -->
                                    <td>
                                        <div class="small">
                                            <span>Used: {{ $log->meta['used_cap'] ?? 0 }} / {{ $log->meta['max_cap'] ?? 100 }}</span><br>
                                            <span>Unit BV: {{ $log->meta['unit_bv'] ?? 50 }}</span><br>
                                            <span class="text-muted">Week: {{ isset($log->meta['week']) ? \Carbon\Carbon::parse($log->meta['week'])->format('d M Y') : 'N/A' }}</span>
                                        </div>
                                    </td>
                                    
                                    <!-- Reference -->
                                    <td>
                                        <span class="text-muted">N/A</span>
                                    </td>
                                    
                                    <!-- Trigger -->
                                    <td>
                                        <div class="small">
                                            <span>System</span><br>
                                            @if(isset($log->meta['triggered_by']))
                                                <small class="text-muted">{{ $log->meta['triggered_by'] }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Remarks -->
                                    <td>
                                        <small class="text-muted">
                                            @if($hasPairs)
                                                Matched pairs. L={{ $log->meta['left_cc'] ?? 0 }}, R={{ $log->meta['right_cc'] ?? 0 }}
                                            @else
                                                No matchable pairs. L={{ $log->meta['left_cc'] ?? 0 }}, R={{ $log->meta['right_cc'] ?? 0 }}
                                            @endif
                                        </small>
                                    </td>
                                    
                                    <!-- Created At -->
                                    <td>
                                        <strong>{{ $log->created_at->format('d M Y H:i') }}</strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                            <h5 class="mb-1">No Pair Matching Logs Found</h5>
                                            <p class="mb-0 small">Your binary pairing history will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($pairLogs->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <label class="text-muted small me-2">Rows per page:</label>
                            <select class="form-select form-select-sm d-inline-block" style="width: 80px;" onchange="updatePerPage(this.value)">
                                <option value="15" {{ $pairLogs->perPage() == 15 ? 'selected' : '' }}>15</option>
                                <option value="30" {{ $pairLogs->perPage() == 30 ? 'selected' : '' }}>30</option>
                                <option value="50" {{ $pairLogs->perPage() == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div>
                            {{ $pairLogs->links() }}
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