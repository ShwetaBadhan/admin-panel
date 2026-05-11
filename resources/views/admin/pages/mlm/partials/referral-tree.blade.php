<div class="referral-tree p-4">
    <h6 class="mb-3">Direct Referrals of {{ $user->user_name }}</h6>
    
    @if($referrals->count() > 0)
        <div class="list-group">
            @foreach($referrals as $ref)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $ref->user_name }}</strong>
                        <small class="d-block text-muted">{{ $ref->first_name }} {{ $ref->last_name }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary">{{ $ref->created_at->format('d M Y') }}</span>
                        <small class="d-block mt-1">{{ $ref->payoutBalance?->cc_balance ?? 0 }} CC</small>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-muted py-3">No direct referrals</p>
    @endif
</div>