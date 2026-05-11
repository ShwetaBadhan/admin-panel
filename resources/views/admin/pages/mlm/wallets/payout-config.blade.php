@extends('admin.layout.admin-master')
@section('title', 'Wallet Payout Configuration')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('wallets.index') }}">Wallets</a></li>
                <li class="breadcrumb-item active">Payout Configuration - {{ $wallet->name }}</li>
            </ol>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>Wallet Payout Configuration
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('wallets.update-payout-config', $wallet) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Payout Schedule *</label>
                            <select name="payout_schedule" class="form-select" required>
                                <option value="DAILY" {{ ($wallet->configuration->payout_schedule ?? '') == 'DAILY' ? 'selected' : '' }}>Daily</option>
                                <option value="WEEKLY" {{ ($wallet->configuration->payout_schedule ?? '') == 'WEEKLY' ? 'selected' : '' }}>Weekly</option>
                                <option value="MONTHLY" {{ ($wallet->configuration->payout_schedule ?? '') == 'MONTHLY' ? 'selected' : '' }}>Monthly</option>
                                <option value="INSTANT" {{ ($wallet->configuration->payout_schedule ?? '') == 'INSTANT' ? 'selected' : '' }}>Instant</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Payout Execution Day</label>
                            <select name="payout_execution_day" class="form-select">
                                <option value="">Select Day</option>
                                <option value="Monday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                <option value="Tuesday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="Wednesday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="Thursday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="Friday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Friday' ? 'selected' : '' }}>Friday</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Refund Window (Days) *</label>
                            <input type="number" name="refund_window_days" class="form-control" 
                                   value="{{ $wallet->configuration->refund_window_days ?? 30 }}" required>
                            <small class="text-muted">Days to hold funds before payout</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Min Withdraw Amount *</label>
                            <input type="number" name="min_withdraw_amount" class="form-control" 
                                   value="{{ $wallet->configuration->min_withdraw_amount ?? 500 }}" 
                                   step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Payouts Per Batch *</label>
                            <input type="number" name="max_payouts_per_batch" class="form-control" 
                                   value="{{ $wallet->configuration->max_payouts_per_batch ?? 500 }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Withdraw Cool-down (Days) *</label>
                            <input type="number" name="withdraw_cooldown_days" class="form-control" 
                                   value="{{ $wallet->configuration->withdraw_cooldown_days ?? 7 }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Start Window</label>
                            <input type="time" name="start_window" class="form-control" 
                                   value="{{ $wallet->configuration->start_window ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">End Window</label>
                            <input type="time" name="end_window" class="form-control" 
                                   value="{{ $wallet->configuration->end_window ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input type="checkbox" name="auto_payout" class="form-check-input" 
                                       {{ ($wallet->configuration->auto_payout ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label">Automated Payout Processing</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Processing Fee (%)</label>
                            <input type="number" name="processing_fee_percent" class="form-control" 
                                   value="{{ $wallet->configuration->processing_fee_percent ?? 0 }}" 
                                   step="0.01" min="0" max="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Processing Fee (Fixed)</label>
                            <input type="number" name="processing_fee_fixed" class="form-control" 
                                   value="{{ $wallet->configuration->processing_fee_fixed ?? 0 }}" 
                                   step="0.01" min="0">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('wallets.index') }}" class="btn btn-light">Discard Changes</a>
                        <button type="submit" class="btn btn-primary">Save Configuration</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection