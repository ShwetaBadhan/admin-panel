{{-- resources/views/admin/pages/mlm/partials/tree-root.blade.php --}}

<div class="tree-level">
    <div class="tree-node-wrapper">
        <div class="user-card root-user" onclick="showUserProfile({{ $node['user_id'] }}, @json($node))">
            <i class="fas fa-crown crown-icon"></i>
            <div class="user-avatar avatar-blue">
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
        <div class="expand-btn" onclick="toggleSubtree(this, 'subtree-root')">
            <i class="fas fa-chevron-down"></i>
        </div>
        @endif
    </div>
</div>

@if($node['left'] || $node['right'])
<div id="subtree-root" class="subtree">
    <div class="tree-level">
        <!-- Left Child -->
        <div class="tree-node-wrapper">
            @if($node['left'])
                @include('admin.pages.mlm.partials.tree-node', ['node' => $node['left'], 'depth' => 1])
            @else
                <div class="empty-slot">
                    <i class="fas fa-user"></i>
                    <div>Empty Slot (Left)</div>
                </div>
            @endif
        </div>
        
        <!-- Right Child -->
        <div class="tree-node-wrapper">
            @if($node['right'])
                @include('admin.pages.mlm.partials.tree-node', ['node' => $node['right'], 'depth' => 1])
            @else
                <div class="empty-slot">
                    <i class="fas fa-user"></i>
                    <div>Empty Slot (Right)</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endif