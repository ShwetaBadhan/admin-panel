<div class="binary-tree p-4">
    <style>
        .tree-node {
            text-align: center;
            padding: 10px;
            margin: 10px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background: white;
            display: inline-block;
            min-width: 150px;
        }
        .tree-node.root { border-color: #667eea; background: #f8f9ff; }
        .tree-node.left { border-color: #28a745; }
        .tree-node.right { border-color: #ffc107; }
        .tree-children {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .tree-connector {
            width: 2px;
            height: 20px;
            background: #dee2e6;
            margin: 0 auto;
        }
    </style>
    
    @if($treeData)
        <div class="text-center mb-3">
            <strong>{{ $user->user_name }}</strong>
            <small class="d-block text-muted">Level {{ $treeData['level'] }} - {{ strtoupper($treeData['position']) }}</small>
        </div>
        
        <div class="tree-children">
            @if($treeData['left'])
                <div class="tree-node left">
                    <strong>{{ $treeData['left']['user']->user_name }}</strong>
                    <small class="d-block text-muted">LEFT</small>
                    <small class="d-block text-primary">{{ $treeData['left']['cc_balance'] }} CC</small>
                </div>
            @else
                <div class="tree-node" style="opacity:0.3">
                    <small class="text-muted">Empty</small>
                </div>
            @endif
            
            @if($treeData['right'])
                <div class="tree-node right">
                    <strong>{{ $treeData['right']['user']->user_name }}</strong>
                    <small class="d-block text-muted">RIGHT</small>
                    <small class="d-block text-primary">{{ $treeData['right']['cc_balance'] }} CC</small>
                </div>
            @else
                <div class="tree-node" style="opacity:0.3">
                    <small class="text-muted">Empty</small>
                </div>
            @endif
        </div>
    @else
        <p class="text-center text-muted">No tree data available</p>
    @endif
</div>