@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <!-- Welcome Header -->
    <div class="col-12 mb-2">
        <h2 class="fw-bold h3 mb-1">Home - <span class="text-secondary fw-normal">Dashboard</span></h2>
        <p class="text-muted small">Welcome back, {{ auth()->user()->name }}! Here's what's happening with your account.</p>
    </div>

    <!-- Stats Cards -->
    <div class="col-12">
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold">Order statistics</h6>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-3 border-end border-secondary border-opacity-10">
                        <div class="p-4 text-center">
                            <div class="text-muted small mb-1">Wallet Balance</div>
                            <h3 class="fw-bold mb-0 text-success">${{ number_format($walletBalance, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 border-end border-secondary border-opacity-10">
                        <div class="p-4 text-center">
                            <div class="text-muted small mb-1">Total Orders</div>
                            <h3 class="fw-bold mb-0 text-white">{{ $totalOrders }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 border-end border-secondary border-opacity-10">
                        <div class="p-4 text-center">
                            <div class="text-muted small mb-1">Completed</div>
                            <h3 class="fw-bold mb-0 text-white">{{ $completedOrders }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-4 text-center">
                            <div class="text-muted small mb-1">Waiting</div>
                            <h3 class="fw-bold mb-0 text-warning">{{ $waitingOrders }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposited & Spent Stats -->
    <div class="col-md-6">
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-4 p-3">
                        <i class="bi bi-cash-stack fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Deposited</div>
                        <div class="h4 fw-bold text-white mb-0">${{ number_format($totalDeposited, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-success bg-opacity-10 rounded-4 p-3">
                        <i class="bi bi-cart-check fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Spent</div>
                        <div class="h4 fw-bold text-white mb-0">${{ number_format($totalSpent, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Graph -->
    <div class="col-lg-8">
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold">Usage graph (Last 7 Days)</h6>
            </div>
            <div class="card-body p-4">
                <div style="height: 350px;">
                    <canvas id="usageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-lg-4">
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold">Recent Orders</h6>
            </div>
            <div class="card-body p-0">
                @php
                    $recentOrders = \App\Models\Order::where('user_id', auth()->id())->with('service')->orderBy('created_at', 'desc')->take(5)->get();
                @endphp
                @if($recentOrders->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentOrders as $order)
                            <div class="list-group-item bg-transparent border-secondary border-opacity-10 px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-white small">{{ $order->service->name ?? 'N/A' }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">{{ $order->number }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-white small">${{ number_format($order->price, 2) }}</div>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'waiting_sms' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $order->status === 'completed' ? 'success' : ($order->status === 'waiting_sms' ? 'warning' : 'danger') }} px-2 py-0 rounded-pill" style="font-size:0.65rem;">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted small mb-0">No orders yet.</p>
                        <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm mt-3">Place Order</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
    .list-group-item { border-bottom: 1px solid rgba(255,255,255,0.08) !important; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('usageChart').getContext('2d');

    // Gradient for the lines
    const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
    gradient1.addColorStop(0, 'rgba(13, 110, 253, 0.2)');
    gradient1.addColorStop(1, 'rgba(13, 110, 253, 0)');

    const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
    gradient2.addColorStop(0, 'rgba(25, 135, 84, 0.2)');
    gradient2.addColorStop(1, 'rgba(25, 135, 84, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Deposited',
                data: {!! json_encode($chartData['deposited']) !!},
                borderColor: '#0d6efd',
                backgroundColor: gradient1,
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#0d6efd'
            }, {
                label: 'Spent',
                data: {!! json_encode($chartData['spent']) !!},
                borderColor: '#198754',
                backgroundColor: gradient2,
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#198754'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        color: '#94a3b8',
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: '#16191d',
                    titleColor: '#fff',
                    bodyColor: '#94a3b8',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: 'rgba(255,255,255,0.03)', drawBorder: false },
                    ticks: { color: '#64748b', padding: 10, font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', padding: 10, font: { size: 11 } }
                }
            }
        }
    });
</script>
@endpush
