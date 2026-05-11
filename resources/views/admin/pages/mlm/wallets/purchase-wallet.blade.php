@extends('admin.layout.admin-master')
@section('title', 'Purchase Wallet')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-titles mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Purchase Wallet</h3>
                    <p class="text-muted mb-0">Manage Purchase Wallet</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Wallet Selector -->
                    <select class="form-select form-select-sm" style="width: 180px;" onchange="updateFilter('wallet', this.value)">
                        <option value="purchase" selected>Purchase Wallet</option>
                        <option value="commission">Commission Wallet</option>
                        <option value="reward">Reward Wallet</option>
                    </select>
                    
                    <!-- Period Filter -->
                    <select class="form-select form-select-sm" style="width: 140px;" onchange="updateFilter('period', this.value)">
                        <option value="all" {{ $period == 'all' ? 'selected' : '' }}>📅 Period</option>
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                    
                    <!-- Transaction Type -->
                    <select class="form-select form-select-sm" style="width: 160px;" onchange="updateFilter('type', this.value)">
                        <option value="all" {{ $typeFilter == 'all' ? 'selected' : '' }}>All Transactions</option>
                        <option value="product_purchase">Product Purchase</option>
                        <option value="package_purchase">Package Purchase</option>
                        <option value="refund">Refund</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                    
                    <!-- Status Filter -->
                    <select class="form-select form-select-sm" style="width: 130px;" onchange="updateFilter('status', this.value)">
                        <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="COMPLETED" {{ $statusFilter == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                        <option value="PENDING" {{ $statusFilter == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="PROCESSING" {{ $statusFilter == 'PROCESSING' ? 'selected' : '' }}>Processing</option>
                        <option value="REFUNDED" {{ $statusFilter == 'REFUNDED' ? 'selected' : '' }}>Refunded</option>
                        <option value="CANCELLED" {{ $statusFilter == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Available Balance -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Available Balance</p>
                        <h3 class="mb-0">₹{{ number_format(0, 2) }}</h3>
                        <small class="text-muted">Purchase wallet balance</small>
                    </div>
                </div>
            </div>

            <!-- Pending Commissions -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Pending Orders</p>
                        <h3 class="mb-0 text-warning">₹{{ number_format($pendingOrders, 2) }}</h3>
                        <small class="text-muted">In processing</small>
                    </div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Total Spent</p>
                        <h3 class="mb-0 text-success">₹{{ number_format($totalSpent, 2) }}</h3>
                        <small class="text-muted">{{ $totalOrders }} orders</small>
                    </div>
                </div>
            </div>

            <!-- Total Withdrawal -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Total Refunded</p>
                        <h3 class="mb-0 text-info">₹{{ number_format($refundedAmount, 2) }}</h3>
                        <small class="text-muted">Refund amount</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Category</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Associated User</th>
                                <th class="pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4">
                                        @php
                                            $categoryMap = [
                                                'SELF' => ['label' => 'Product Purchase', 'icon' => 'fa-shopping-cart', 'color' => 'primary'],
                                                'PACKAGE' => ['label' => 'Package Purchase', 'icon' => 'fa-box', 'color' => 'success'],
                                                'REFUND' => ['label' => 'Refund', 'icon' => 'fa-undo', 'color' => 'info'],
                                            ];
                                            $category = $categoryMap[$order->order_type] ?? ['label' => 'Purchase', 'icon' => 'fa-shopping-bag', 'color' => 'secondary'];
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="bg-{{ $category['color'] }} bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas {{ $category['icon'] }} text-{{ $category['color'] }}"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $category['label'] }}</strong>
                                                <small class="d-block text-muted">Order #{{ $order->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">₹{{ number_format($order->total_amount, 2) }}</strong>
                                        @if($order->total_cc_points > 0)
                                            <br><small class="text-muted">{{ $order->total_cc_points }} CC</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'COMPLETED' => 'bg-success',
                                                'PENDING' => 'bg-warning text-dark',
                                                'PROCESSING' => 'bg-info text-dark',
                                                'REFUNDED' => 'bg-primary',
                                                'CANCELLED' => 'bg-secondary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} bg-opacity-10 text-{{ $order->status === 'COMPLETED' ? 'success' : ($order->status === 'PENDING' ? 'warning' : ($order->status === 'CANCELLED' ? 'secondary' : 'primary')) }}">
                                            {{ ucfirst(strtolower($order->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->order_date->format('d M Y') }}</strong>
                                            <br><small class="text-muted">{{ $order->order_date->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong>
                                            <br><small class="text-muted">@{{ $order->user->user_name }}</small>
                                        </div>
                                    </td>
                                    <td class="pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="viewOrder({{ $order->id }})">
                                                        <i class="fas fa-eye me-2"></i> View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="downloadInvoice({{ $order->id }})">
                                                        <i class="fas fa-file-invoice me-2"></i> Download Invoice
                                                    </a>
                                                </li>
                                                @if($order->status === 'COMPLETED')
                                                
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="requestRefund({{ $order->id }})">
                                                        <i class="fas fa-undo me-2"></i> Request Refund
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                            <h5 class="mb-1">No Transaction Found</h5>
                                            <p class="mb-0 small">Your purchase history will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <label class="text-muted small me-2">Rows per page:</label>
                            <select class="form-select form-select-sm d-inline-block" style="width: 80px;" onchange="updatePerPage(this.value)">
                                <option value="10" {{ $orders->perPage() == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $orders->perPage() == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $orders->perPage() == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div>
                            {{ $orders->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 text-muted">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Update filter
function updateFilter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.location.href = url.toString();
}

// Update rows per page
function updatePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}

// View order details
function viewOrder(orderId) {
    const modal = document.getElementById('orderDetailModal');
    const content = document.getElementById('orderDetailContent');
    const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
    
    content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading...</p></div>';
    bsModal.show();
    
    // Fetch order details (you can create an API endpoint for this)
    setTimeout(() => {
        content.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Order Information</h6>
                    <table class="table table-sm">
                        <tr><td class="text-muted">Order ID</td><td>#${orderId}</td></tr>
                        <tr><td class="text-muted">Date</td><td>${new Date().toLocaleDateString()}</td></tr>
                        <tr><td class="text-muted">Status</td><td><span class="badge bg-success">Completed</span></td></tr>
                        <tr><td class="text-muted">Payment</td><td>Cash</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Items</h6>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Product A (x2)</span>
                            <strong>₹1,000</strong>
                        </li>
                    </ul>
                </div>
            </div>
        `;
    }, 500);
}

// Download invoice
function downloadInvoice(orderId) {
    Swal.fire({
        title: 'Downloading Invoice',
        text: 'Your invoice is being generated...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    });
    // Add your invoice download logic here
}

// Request refund
function requestRefund(orderId) {
    Swal.fire({
        title: 'Request Refund?',
        text: 'Are you sure you want to request a refund for this order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Request',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Requested!', 'Refund request submitted.', 'success');
        }
    });
}
</script>
@endpush
@endsection