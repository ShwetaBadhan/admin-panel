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
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ✅ Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tree script loaded');
    
    const treeWrapper = document.querySelector('.tree-wrapper');
    if (!treeWrapper) {
        console.error('Tree wrapper not found');
        return;
    }

    // ✅ Event delegation for ALL clicks in tree
    treeWrapper.addEventListener('click', function(e) {
        console.log('Click detected', e.target);
        
        // 1️⃣ Profile card click → Open modal
        const profileCard = e.target.closest('.profile-clickable');
        if (profileCard) {
            console.log('Profile clicked');
            e.preventDefault();
            e.stopPropagation();
            
            const userId = profileCard.dataset.userId;
            console.log('User ID:', userId);
            
            if (userId) {
                openProfileModal(userId);
            }
            return;
        }

        // 2️⃣ Expand button click → Toggle subtree
        const toggleBtn = e.target.closest('.subtree-toggle');
        if (toggleBtn) {
            console.log('Toggle button clicked');
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = toggleBtn.dataset.target;
            console.log('Target subtree ID:', targetId);
            
            const subtree = document.getElementById(targetId);
            if (subtree) {
                console.log('Subtree found, toggling...');
                subtree.classList.toggle('show');
                toggleBtn.classList.toggle('expanded');
                
                // Toggle icon
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    if (subtree.classList.contains('show')) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    } else {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    }
                }
            } else {
                console.error('Subtree not found:', targetId);
            }
        }
    });

    // ✅ Modal function
    function openProfileModal(userId) {
        const modalEl = document.getElementById('userProfileModal');
        const modal = new bootstrap.Modal(modalEl);
        const contentEl = document.getElementById('profileContent');
        
        // Show loading
        contentEl.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Loading profile...</p>
            </div>`;
        
        modal.show();

        // Fetch modal content
        fetch(`/team-genealogy/user/${userId}/modal`)
            .then(res => {
                if (!res.ok) {
                    throw new Error('HTTP ' + res.status);
                }
                return res.text();
            })
            .then(html => {
                contentEl.innerHTML = html;
            })
            .catch(err => {
                console.error('Modal error:', err);
                contentEl.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Failed to load profile. Please try again.
                    </div>`;
            });
    }
});
</script>
<style>
/* Tree structure */
.tree-wrapper {
    text-align: center;
    padding: 20px;
    overflow-x: auto;
}

.tree-level {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 20px;
    margin: 20px 0;
}

.tree-node-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.user-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    min-width: 180px;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
}

.user-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 18px;
    margin: 0 auto 10px;
}

.avatar-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.avatar-orange {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.user-name {
    font-weight: 600;
    font-size: 14px;
    color: #2d3748;
    margin-bottom: 5px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: #718096;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #48bb78;
}

/* Expand button */
.expand-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin-top: 10px;
    transition: all 0.2s;
    border: none;
}

.expand-btn:hover {
    background: #cbd5e0;
    transform: scale(1.1);
}

.expand-btn.expanded i {
    transform: rotate(180deg);
}

.expand-btn i {
    transition: transform 0.2s;
    font-size: 12px;
    color: #4a5568;
}

/* Connector */
.connector-vertical {
    width: 2px;
    height: 30px;
    background: #e2e8f0;
    margin: 0 auto;
}

/* Subtree - initially hidden */
.subtree {
    display: none;
    margin-top: 20px;
}

.subtree.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Empty slot */
.empty-slot {
    background: #f7fafc;
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    min-width: 180px;
    color: #a0aec0;
}

.empty-slot i {
    font-size: 30px;
    margin-bottom: 10px;
}

/* Crown icon */
.crown-icon {
    position: absolute;
    top: -10px;
    right: -10px;
    color: #f6e05e;
    font-size: 20px;
}
</style>
@endpush