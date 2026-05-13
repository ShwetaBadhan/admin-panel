<div class="modal-profile-avatar">
    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
    <span class="modal-status-dot" style="background: {{ $user->is_active ? '#28a745' : '#dc3545' }}"></span>
</div>

<h4 class="modal-title mt-2">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</h4>

{{-- ✅ CORRECT: Use @ before {{ to show @ symbol --}}
<p class="modal-subtitle">{{ '@' }}{{ $user->user_name ?? 'N/A' }} 
    ({{ (isset($stats['sponsor_id']) && $stats['sponsor_id'] !== 'Direct Seller') ? 'Sponsor: ' . $stats['sponsor_id'] : 'Direct Seller' }})
</p>

<div class="stats-grid mt-3">
    <div class="stat-card">
        <div class="stat-label">User ID</div>
        <div class="stat-value" style="font-size: 13px;">{{ $user->user_name ?? 'N/A' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sponsor ID</div>
        <div class="stat-value" style="font-size: 13px;">{{ $stats['sponsor_id'] ?? 'N/A' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Current Right CC</div>
        <div class="stat-value" style="font-size: 14px; color: #dc3545;">{{ $stats['current_right_cc'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Current Left CC</div>
        <div class="stat-value" style="font-size: 14px; color: #0d6efd;">{{ $stats['current_left_cc'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Active Right Team</div>
        <div class="stat-value">{{ $stats['active_right_team'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Active Left Team</div>
        <div class="stat-value">{{ $stats['active_left_team'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Right Team</div>
        <div class="stat-value">{{ $stats['total_right_team'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Left Team</div>
        <div class="stat-value">{{ $stats['total_left_team'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Joining Date</div>
        <div class="stat-value" style="font-size: 13px;">{{ $stats['joined_date'] ?? 'N/A' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Self CC</div>
        <div class="stat-value">{{ $stats['personal_bv'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Package</div>
        <div class="stat-value" style="font-size: 13px;">{{ $stats['package'] ?? 'N/A' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Level</div>
        <div class="stat-value">{{ $stats['level'] ?? 0 }}</div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Close</button>
    <a href="{{ route('team-genealogy.downline', $user->id) }}" 
       class="btn btn-primary flex-fill" 
       style="background: #1e3a5f; border: none;">
        <i class="fas fa-sitemap me-1"></i> My Downline
    </a>
</div>