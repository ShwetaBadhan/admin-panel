{{-- resources/views/admin/pages/mlm/partials/tree-root.blade.php --}}
<div class="tree-level">
    <div class="tree-node-wrapper">
        <div class="user-card root-user profile-clickable" 
             data-user-id="{{ $node['user_id'] ?? '' }}"
             style="cursor: pointer;">
            <i class="fas fa-crown crown-icon"></i>
            <div class="user-avatar avatar-blue">
                {{ strtoupper(substr($node['first_name'] ?? 'U', 0, 1)) }}{{ strtoupper(substr($node['last_name'] ?? '', 0, 1)) }}
            </div>
            <div class="user-name">{{ $node['first_name'] ?? '' }} {{ $node['last_name'] ?? '' }}</div>
            <div class="status-badge">
                <span class="status-dot"></span>
                {{ ($node['is_active'] ?? false) ? 'Active' : 'Inactive' }}
            </div>
        </div>
        
        @if($node['left'] || $node['right'])
        <div class="connector-vertical"></div>
        <div class="expand-btn subtree-toggle" 
             data-target="subtree-root"
             style="cursor: pointer;">
            <i class="fas fa-chevron-down"></i>
        </div>
        @endif
    </div>
</div>

@if($node['left'] || $node['right'])
{{-- ❌ NO "show" class - initially hidden --}}
<div id="subtree-root" class="subtree">
    <div class="tree-level">
        <div class="tree-node-wrapper">
            @if($node['left'])
                @include('admin.pages.mlm.partials.tree-node', ['node' => $node['left'], 'depth' => 1])
            @else
                <div class="empty-slot"><i class="fas fa-user"></i><div>Empty Slot (Left)</div></div>
            @endif
        </div>
        <div class="tree-node-wrapper">
            @if($node['right'])
                @include('admin.pages.mlm.partials.tree-node', ['node' => $node['right'], 'depth' => 1])
            @else
                <div class="empty-slot"><i class="fas fa-user"></i><div>Empty Slot (Right)</div></div>
            @endif
        </div>
    </div>
</div>
@endif