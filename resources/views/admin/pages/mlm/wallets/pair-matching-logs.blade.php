@extends('admin.layout.admin-master')
@section('title', 'Pair Matching Logs')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-4">
            <h3 class="mb-1">Pair Matching Logs</h3>
            <p class="text-muted mb-0">Track binary/matrix pair matches, income generation, and payout status.</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Pair Match History</h5>
                <span class="badge bg-primary">Total Income: ₹{{ number_format($totalPairIncome, 2) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Left / Right Pair</th>
                                <th>Pairs</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pairLogs as $log)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <strong>{{ $log->user_name ?? $log->mlm_user_id ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $log->reference_id ?? 'Pair Match' }}</small>
                                    </td>
                                    <td>{{ $log->left_user ?? '—' }} / {{ $log->right_user ?? '—' }}</td>
                                    <td>{{ $log->pair_count ?? $log->pairs ?? 1 }}</td>
                                    <td class="fw-bold text-success">₹{{ number_format($log->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ ($log->status ?? 'credited') === 'credited' ? 'success' : 'warning' }}">
                                            {{ ucfirst($log->status ?? 'credited') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No pair matching logs found yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ✅ Safe pagination (prevents Collection::links crash) --}}
                @if($pairLogs instanceof \Illuminate\Pagination\LengthAwarePaginator && $pairLogs->hasPages())
                    <div class="p-3">{{ $pairLogs->links('pagination::bootstrap-5') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection