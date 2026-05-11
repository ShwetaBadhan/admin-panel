@extends('admin.layout.admin-master')
@section('title', 'Wallet Charges')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('wallets.index') }}">Wallets</a></li>
                <li class="breadcrumb-item active">Charges - {{ $wallet->name }}</li>
            </ol>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>Wallet Charges Configuration
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('wallets.update-charges', $wallet) }}" method="POST">
                    @csrf
                    <div id="chargesContainer">
                        @foreach($wallet->charges as $index => $charge)
                            <div class="charge-item border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Charge Type</label>
                                        <input type="text" name="charges[{{ $index }}][charge_type]" 
                                               class="form-control" value="{{ $charge->charge_type }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Charge Mode</label>
                                        <select name="charges[{{ $index }}][charge_mode]" class="form-select" required>
                                            <option value="FIXED" {{ $charge->charge_mode == 'FIXED' ? 'selected' : '' }}>Fixed Amount</option>
                                            <option value="PERCENTAGE" {{ $charge->charge_mode == 'PERCENTAGE' ? 'selected' : '' }}>Percentage (%)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Charge Value *</label>
                                        <input type="number" name="charges[{{ $index }}][charge_value]" 
                                               class="form-control" value="{{ $charge->charge_value }}" 
                                               step="0.01" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Min Charge</label>
                                        <input type="number" name="charges[{{ $index }}][min_charge]" 
                                               class="form-control" value="{{ $charge->min_charge }}" 
                                               step="0.01">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Max Charge</label>
                                        <input type="number" name="charges[{{ $index }}][max_charge]" 
                                               class="form-control" value="{{ $charge->max_charge }}" 
                                               step="0.01">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Add new charge button -->
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCharge()">
                            <i class="fas fa-plus me-1"></i> Add Another Charge
                        </button>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('wallets.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Charges</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let chargeIndex = {{ $wallet->charges->count() }};

function addCharge() {
    const container = document.getElementById('chargesContainer');
    const html = `
        <div class="charge-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Charge Type</label>
                    <input type="text" name="charges[${chargeIndex}][charge_type]" 
                           class="form-control" placeholder="e.g., Withdrawal Fee" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Charge Mode</label>
                    <select name="charges[${chargeIndex}][charge_mode]" class="form-select" required>
                        <option value="FIXED">Fixed Amount</option>
                        <option value="PERCENTAGE">Percentage (%)</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Charge Value *</label>
                    <input type="number" name="charges[${chargeIndex}][charge_value]" 
                           class="form-control" step="0.01" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Min Charge</label>
                    <input type="number" name="charges[${chargeIndex}][min_charge]" 
                           class="form-control" step="0.01">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Max Charge</label>
                    <input type="number" name="charges[${chargeIndex}][max_charge]" 
                           class="form-control" step="0.01">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.charge-item').remove()">
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    chargeIndex++;
}
</script>
@endpush
@endsection