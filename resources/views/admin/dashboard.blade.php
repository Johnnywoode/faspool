@extends('layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0">Admin Dashboard</h1>
        <p class="text-secondary small mb-0">Platform-wide overview</p>
    </div>
    <span class="badge bg-primary-subtle text-primary px-3 py-2">
        <i class="bi bi-shield-check me-1"></i> Admin
    </span>
</div>

{{-- Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 h-100" style="background:var(--bs-secondary-bg)">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(99,102,241,.15)">
                    <i class="bi bi-people fs-4 text-indigo"></i>
                </div>
                <div>
                    <p class="text-secondary small mb-0">Total Users</p>
                    <h3 class="fw-bold mb-0">{{ number_format($totalUsers) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 h-100" style="background:var(--bs-secondary-bg)">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(16,185,129,.15)">
                    <i class="bi bi-bag-check fs-4 text-success"></i>
                </div>
                <div>
                    <p class="text-secondary small mb-0">Total Orders</p>
                    <h3 class="fw-bold mb-0">{{ number_format($totalOrders) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 h-100" style="background:var(--bs-secondary-bg)">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(245,158,11,.15)">
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <div>
                    <p class="text-secondary small mb-0">Waiting SMS</p>
                    <h3 class="fw-bold mb-0">{{ number_format($waitingOrders) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 h-100" style="background:var(--bs-secondary-bg)">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(59,130,246,.15)">
                    <i class="bi bi-currency-dollar fs-4 text-info"></i>
                </div>
                <div>
                    <p class="text-secondary small mb-0">Total Revenue</p>
                    <h3 class="fw-bold mb-0">GH₵ {{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart --}}
<div class="card border-0 mb-4" style="background:var(--bs-secondary-bg)">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Last 7 Days — Revenue & Orders</h6>
        <canvas id="adminChart" height="90"></canvas>
    </div>
</div>

{{-- Quick Links --}}
<div class="row g-3">
    <div class="col-md-4">
        <a href="{{ route('admin.users.index') }}" class="card border-0 text-decoration-none h-100" style="background:var(--bs-secondary-bg)">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-people fs-3 text-primary"></i>
                <span class="fw-semibold">Manage Users</span>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.providers.index') }}" class="card border-0 text-decoration-none h-100" style="background:var(--bs-secondary-bg)">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-hdd-network fs-3 text-success"></i>
                <span class="fw-semibold">Manage Providers</span>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.tenants.index') }}" class="card border-0 text-decoration-none h-100" style="background:var(--bs-secondary-bg)">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-building fs-3 text-warning"></i>
                <span class="fw-semibold">Manage Tenants</span>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('adminChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Revenue (GH₵)',
                    data: @json($chartData['revenue']),
                    backgroundColor: 'rgba(99,102,241,0.7)',
                    borderRadius: 6,
                    yAxisID: 'y',
                },
                {
                    label: 'Orders',
                    data: @json($chartData['orders']),
                    type: 'line',
                    borderColor: 'rgba(16,185,129,1)',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    tension: 0.4,
                    pointRadius: 4,
                    fill: true,
                    yAxisID: 'y1',
                },
            ],
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { labels: { color: '#94a3b8' } } },
            scales: {
                x:  { ticks: { color: '#64748b' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                y:  { position: 'left',  ticks: { color: '#64748b' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                y1: { position: 'right', ticks: { color: '#64748b' }, grid: { drawOnChartArea: false } },
            },
        },
    });
</script>
@endpush
