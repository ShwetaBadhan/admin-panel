{{-- resources/views/admin/pages/mlm/partials/tree-node.blade.php --}}

@php
    $avatarClass = ($node['user_name'] === 'Founder01' || ($node['is_root'] ?? false)) ? 'avatar-blue' : 'avatar-orange';
    $subtreeId = 'subtree-' . $node['id'];
@endphp

<div class="user-card" onclick="showUserProfile({{ $node['user_id'] }}, @json($node))">
    <div class="user-avatar {{ $avatarClass }}">
        {{ strtoupper(substr($node['first_name'], 0, 1)) }}{{ strtoupper(substr($node['last_name'], 0, 1)) }}
    </div>
    <div class="user-name">{{ $node['first_name'] }} {{ $node['last_name'] }}</div>
    {{-- <div class="user-handle">@{{ $node['user_name'] }}</div> --}}
    <div class="status-badge">
        <span class="status-dot"></span>
        {{ $node['is_active'] ? 'Active' : 'Inactive' }}
    </div>
</div>

@if($node['left'] || $node['right'])
<div class="connector-vertical"></div>
<div class="expand-btn" onclick="toggleSubtree(this, '{{ $subtreeId }}')">
    <i class="fas fa-chevron-down"></i>
</div>

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