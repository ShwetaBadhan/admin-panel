@extends('admin.layout.admin-master')
@section('title', 'Bonus History')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-4">
            <h3 class="mb-1">Bonus History</h3>
            <p class="text-muted mb-0">Complete log of all bonus payouts and earnings.</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Bonus Records</h5>
                <span class="badge bg-primary">Total: ₹{{ number_format($totalBonuses, 2) }}</span>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bonuses as $bonus)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($bonus->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <strong>{{ $bonus->user_name ?? $bonus->mlm_user_id ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $bonus->reference_id ?? $bonus->description ?? 'Bonus' }}</small>
                                    </td>
                                    <td>{{ ucfirst($bonus->type ?? $bonus->bonus_type ?? 'bonus') }}</td>
                                    <td class="fw-bold text-success">{{ number_format($bonus->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ ($bonus->status ?? 'credited') === 'credited' ? 'success' : 'warning' }}">
                                            {{ ucfirst($bonus->status ?? 'credited') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No bonus history found yet.
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