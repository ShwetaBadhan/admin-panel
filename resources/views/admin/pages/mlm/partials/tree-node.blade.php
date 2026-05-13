{{-- resources/views/admin/pages/mlm/partials/tree-node.blade.php --}}
@php
    $avatarClass = ($node['user_name'] === 'Founder01' || ($node['is_root'] ?? false)) 
        ? 'avatar-blue' 
        : 'avatar-orange';
    $subtreeId = 'subtree-' . $node['id'];
@endphp

<div class="user-card profile-clickable" 
     data-user-id="{{ $node['user_id'] }}"
     style="cursor: pointer;">
    <div class="user-avatar {{ $avatarClass }}">
        {{ strtoupper(substr($node['first_name'], 0, 1)) }}{{ strtoupper(substr($node['last_name'], 0, 1)) }}
    </div>
    <div class="user-name">{{ $node['first_name'] }} {{ $node['last_name'] }}</div>
    <div class="status-badge">
        <span class="status-dot"></span>
        {{ $node['is_active'] ? 'Active' : 'Inactive' }}
    </div>
</div>

@if($node['left'] || $node['right'])
<div class="connector-vertical"></div>
<div class="expand-btn subtree-toggle" 
     data-target="{{ $subtreeId }}"
     style="cursor: pointer;">
    <i class="fas fa-chevron-down"></i>
</div>

{{-- ❌ NO "show" class - initially hidden --}}
<div id="{{ $subtreeId }}" class="subtree">
    <div class="tree-level">
        <div class="tree-node-wrapper">
            @if($node['left'])
                @include('admin.pages.mlm.partials.tree-node', ['node' => $node['left'], 'depth' => $depth + 1])
            @else
                <div class="empty-slot">
                    <i class="fas fa-user"></i>
                    <div>Empty Slot (LS)</div>
                </div>
            @endif
        </div>
        
        <div class="tree-node-wrapper">
            @if($node['right'])
                @include('admin.pages.mlm.partials.tree-node', ['node' => $node['right'], 'depth' => $depth + 1])
            @else
                <div class="empty-slot">
                    <i class="fas fa-user"></i>
                    <div>Empty Slot (RS)</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endif