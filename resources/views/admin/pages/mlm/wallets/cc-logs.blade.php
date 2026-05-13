@extends('admin.layout.admin-master')
@section('title', 'CC Logs')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-4">
            <h3 class="mb-1">CC (Credit Circle) Logs</h3>
            <p class="text-muted mb-0">Track all CC transactions, conversions, and allocations.</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Transaction History</h5>
                <span class="badge bg-primary">Total CC: {{ number_format($totalCC, 2) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>User / Reference</th>
                                <th>Type</th>
                                <th>CC Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ccLogs as $log)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <strong>{{ $log->user_name ?? $log->mlm_user_id ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $log->reference_id ?? $log->description ?? 'CC Transaction' }}</small>
                                    </td>
                                    <td>{{ ucfirst($log->type ?? $log->cc_type ?? 'allocation') }}</td>
                                    <td class="fw-bold text-info">{{ number_format($log->amount ?? $log->cc_amount ?? 0, 2) }} CC</td>
                                    <td>
                                        <span class="badge bg-{{ ($log->status ?? 'completed') === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($log->status ?? 'completed') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No CC logs found yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ✅ Safe pagination --}}
                @if($ccLogs instanceof \Illuminate\Pagination\LengthAwarePaginator && $ccLogs->hasPages())
                    <div class="p-3">{{ $ccLogs->links('pagination::bootstrap-5') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection