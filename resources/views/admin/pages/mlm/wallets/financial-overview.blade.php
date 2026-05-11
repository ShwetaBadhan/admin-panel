@extends('admin.layout.admin-master')
@section('title', 'Financial Overview')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-titles mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Financial Overview</h3>
                    <p class="text-muted mb-0">Track your investments, earnings, and withdrawals in real-time.</p>
                </div>
                <button class="btn btn-light" onclick="exportReport()">
                    <i class="fas fa-download me-2"></i>Export Report
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Investments -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="avatar-lg bg-primary bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                    <i class="fas fa-wallet text-primary fa-lg"></i>
                                </div>
                                <p class="text-muted mb-1">Total Investments</p>
                                <h3 class="mb-0">₹{{ number_format($totalInvestments, 2) }}</h3>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success">+12.5%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bonus Income -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="avatar-lg bg-success bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                    <i class="fas fa-chart-line text-success fa-lg"></i>
                                </div>
                                <p class="text-muted mb-1">Bonus Income</p>
                                <h3 class="mb-0">₹{{ number_format($bonusIncome, 2) }}</h3>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success">+8.2%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Withdrawals -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="avatar-lg bg-warning bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                    <i class="fas fa-clock text-warning fa-lg"></i>
                                </div>
                                <p class="text-muted mb-1">Pending Withdrawals</p>
                                <h3 class="mb-0">₹{{ number_format($pendingWithdrawals, 2) }}</h3>
                            </div>
                            <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Withdrawals -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="avatar-lg bg-info bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                    <i class="fas fa-check-circle text-info fa-lg"></i>
                                </div>
                                <p class="text-muted mb-1">Completed Withdrawals</p>
                                <h3 class="mb-0">₹{{ number_format($completedWithdrawals, 2) }}</h3>
                            </div>
                            <span class="badge bg-info bg-opacity-10 text-info">Processed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Platform Charges -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="avatar-lg bg-danger bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                    <i class="fas fa-credit-card text-danger fa-lg"></i>
                                </div>
                                <p class="text-muted mb-1">Platform Charges</p>
                                <h3 class="mb-0">₹{{ number_format($platformCharges, 2) }}</h3>
                            </div>
                            <span class="badge bg-danger bg-opacity-10 text-danger">-2.5%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TDS Deducted -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="avatar-lg bg-purple bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                    <i class="fas fa-percentage text-purple fa-lg"></i>
                                </div>
                                <p class="text-muted mb-1">TDS Deducted</p>
                                <h3 class="mb-0">₹{{ number_format($tdsDeducted, 2) }}</h3>
                            </div>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Tax</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4">
            <!-- Financial Comparison Chart -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Financial Comparison</h5>
                        <p class="text-muted mb-4">Absolute value comparison</p>
                        <canvas id="financialChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Distribution Chart -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Distribution</h5>
                        <p class="text-muted mb-4">Percentage breakdown</p>
                        <div class="position-relative" style="height: 250px;">
                            <canvas id="distributionChart"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <p class="text-muted mb-1">Net Income</p>
                                <h4 class="mb-0">₹{{ number_format($netIncome, 2) }}</h4>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-center flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2" style="width: 12px; height: 12px; border-radius: 50%;"></span>
                                    <small class="text-muted">Investments</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2" style="width: 12px; height: 12px; border-radius: 50%;"></span>
                                    <small class="text-muted">Bonus Income</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2" style="width: 12px; height: 12px; border-radius: 50%;"></span>
                                    <small class="text-muted">Pending</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info me-2" style="width: 12px; height: 12px; border-radius: 50%;"></span>
                                    <small class="text-muted">Completed</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger me-2" style="width: 12px; height: 12px; border-radius: 50%;"></span>
                                    <small class="text-muted">Charges & TDS</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('styles')
<style>
    .avatar-lg {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .text-purple {
        color: #6f42c1 !important;
    }
    .bg-purple {
        background-color: #6f42c1 !important;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Financial Comparison Chart
const financialCtx = document.getElementById('financialChart').getContext('2d');
const financialChart = new Chart(financialCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
        datasets: [
            {
                label: 'Investments',
                data: {!! json_encode(array_fill(0, count($monthlyData), $totalInvestments / 6)) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.8)',
                borderRadius: 6,
            },
            {
                label: 'Bonus Income',
                data: {!! json_encode(array_fill(0, count($monthlyData), $bonusIncome / 6)) !!},
                backgroundColor: 'rgba(25, 135, 84, 0.8)',
                borderRadius: 6,
            },
            {
                label: 'Pending',
                data: {!! json_encode(array_fill(0, count($monthlyData), 0)) !!},
                backgroundColor: 'rgba(255, 193, 7, 0.8)',
                borderRadius: 6,
            },
            {
                label: 'Completed',
                data: {!! json_encode(array_fill(0, count($monthlyData), 0)) !!},
                backgroundColor: 'rgba(13, 202, 240, 0.8)',
                borderRadius: 6,
            },
            {
                label: 'Charges & TDS',
                data: {!! json_encode(array_fill(0, count($monthlyData), 0)) !!},
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₹' + value.toLocaleString('en-IN');
                    }
                }
            }
        }
    }
});

// Distribution Chart
const distributionCtx = document.getElementById('distributionChart').getContext('2d');
const distributionChart = new Chart(distributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Investments', 'Bonus Income', 'Pending', 'Completed', 'Charges & TDS'],
        datasets: [{
            data: [
                {{ $distributionData['investments'] }},
                {{ $distributionData['bonus_income'] }},
                {{ $distributionData['pending'] }},
                {{ $distributionData['completed'] }},
                {{ $distributionData['charges_tds'] }}
            ],
            backgroundColor: [
                'rgba(13, 110, 253, 0.8)',
                'rgba(25, 135, 84, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(13, 202, 240, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '75%',
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush