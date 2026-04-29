{{-- resources/views/admin/pages/mlm/team-genealogy.blade.php --}}
@extends('admin.layout.admin-master')

@section('title', 'Team Genealogy')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Team Genealogy</li>
            </ol>
        </div>

        <div class="tree-wrapper">
            @if($treeData)
                @include('admin.pages.mlm.partials.tree-root', ['node' => $treeData])
            @else
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No tree data found.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- User Profile Modal -->
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0 text-center">
                <div id="profileContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function toggleSubtree(btn, subtreeId) {
    const subtree = document.getElementById(subtreeId);
    if (subtree) {
        subtree.classList.toggle('show');
        btn.classList.toggle('expanded');
    }
    event.stopPropagation();
}

function showUserProfile(userId, userData) {
    $('#userProfileModal').modal('show');
    
    $.get(`/team-genealogy/user/${userId}`, function(data) {
        const stats = data.stats;
        const user = data.user;
        
        const initials = (user.first_name.charAt(0) + user.last_name.charAt(0)).toUpperCase();
        const sponsorText = stats.sponsor && stats.sponsor !== 'Direct Seller' 
            ? `Sponsor: ${stats.sponsor}` 
            : 'Direct Seller';
        
        const html = `
            <div class="modal-profile-avatar">
                ${initials}
                <span class="modal-status-dot"></span>
            </div>
            
            <h4 class="modal-title">${user.first_name} ${user.last_name}</h4>
            <p class="modal-subtitle">@${user.user_name} (${sponsorText})</p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Joined Date</div>
                    <div class="stat-value" style="font-size: 14px;">${stats.joined_date}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Level</div>
                    <div class="stat-value" style="font-size: 16px;">${stats.level}</div>
                </div>
                <div class="stat-card personal-bv">
                    <div class="stat-label">Personal BV</div>
                    <div class="stat-value">${stats.personal_bv || 0}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total BV</div>
                    <div class="stat-value">${stats.total_bv || 0}</div>
                </div>
                <div class="stat-card left-bv">
                    <div class="stat-label">Left Team BV</div>
                    <div class="stat-value">${stats.left_team_bv || 0}</div>
                </div>
                <div class="stat-card right-bv">
                    <div class="stat-label">Right Team BV</div>
                    <div class="stat-value">${stats.right_team_bv || 0}</div>
                </div>
                <div class="stat-card network-bv">
                    <div class="stat-label">Network BV</div>
                    <div class="stat-value">${stats.network_bv || 0}</div>
                </div>
                <div class="stat-card turnover">
                    <div class="stat-label">Personal Turnover</div>
                    <div class="stat-value">${stats.personal_turnover || 0}</div>
                </div>
            </div>
            
            <button class="modal-close-btn" data-bs-dismiss="modal">Close Profile</button>
        `;
        
        $('#profileContent').html(html);
    }).fail(function() {
        $('#profileContent').html('<div class="alert alert-danger">Failed to load profile</div>');
    });
}
</script>
@endsection