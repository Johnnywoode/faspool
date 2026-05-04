@extends('layouts.master')

@section('title', 'Platform Analytics')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">Platform Analytics</h2>
                <p class="text-muted small">Deep dive into platform performance, revenue, and user behavior.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-calendar-range me-2"></i> Last 30 Days
                </button>
                <button class="btn btn-primary rounded-3">
                    <i class="bi bi-download me-2"></i> Export Data
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small mb-2 text-uppercase fw-bold">Total Revenue</div>
                        <div class="h3 fw-bold mb-0 text-white">GHS {{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                        <div class="text-success small mt-2"><i class="bi bi-arrow-up"></i> 8.5% vs last month</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small mb-2 text-uppercase fw-bold">Total Orders</div>
                        <div class="h3 fw-bold mb-0 text-white">{{ number_format($stats['total_orders'] ?? 0) }}</div>
                        <div class="text-success small mt-2"><i class="bi bi-arrow-up"></i> 12.3% vs last month</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small mb-2 text-uppercase fw-bold">Active Users</div>
                        <div class="h3 fw-bold mb-0 text-white">{{ number_format($stats['total_users'] ?? 0) }}</div>
                        <div class="text-primary small mt-2">Across all tenants</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small mb-2 text-uppercase fw-bold">Total Tenants</div>
                        <div class="h3 fw-bold mb-0 text-white">{{ number_format($stats['total_tenants'] ?? 0) }}</div>
                        <div class="text-muted small mt-2">SaaS Customers</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Revenue Chart -->
            <div class="col-lg-8">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                        <h5 class="card-title mb-0 text-white">Revenue Growth</h5>
                    </div>
                    <div class="card-body p-4">
                        <div style="height: 350px;">
                            <canvas id="revenueGrowthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue by Tenant -->
            <div class="col-lg-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                        <h5 class="card-title mb-0 text-white">Top Tenants</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0 align-middle">
                                <thead class="bg-secondary bg-opacity-5">
                                    <tr>
                                        <th class="px-4 py-3 small text-uppercase text-secondary">Tenant</th>
                                        <th class="py-3 text-end px-4 small text-uppercase text-secondary">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- This would be populated from AnalyticsController@revenue --}}
                                    @forelse($revenueByTenant ?? [] as $item)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-white small">{{ $item->name }}</div>
                                            </td>
                                            <td class="py-3 text-end px-4 text-success fw-bold small">
                                                GHS {{ number_format($item->total, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted small">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueGrowthChart').getContext('2d');
        
        const data = @json($revenueData ?? []);
        const labels = data.map(item => item.date);
        const totals = data.map(item => item.total);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Daily Revenue',
                    data: totals.length ? totals : [0, 0, 0, 0, 0, 0],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>
@endpush
