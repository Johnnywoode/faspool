@extends('layouts.master')

@section('title', 'Tenant Report: ' . $tenant->name)

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}">Tenants</a></li>
                        <li class="breadcrumb-item active">{{ $tenant->name }}</li>
                    </ol>
                </nav>
                <h2 class="fw-bold h3 mb-0">{{ $tenant->name }} <span class="badge bg-success bg-opacity-10 text-success ms-2 small" style="font-size: 0.5em; vertical-align: middle;">{{ $tenant->status }}</span></h2>
                <p class="text-muted small mb-0">UID: {{ $tenant->uid }} | Domain: {{ $tenant->domain }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-pencil me-2"></i> Edit Tenant
                </a>
                <button class="btn btn-primary rounded-3">
                    <i class="bi bi-download me-2"></i> Export Report
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-2 text-uppercase fw-bold">Total Users</div>
                        <div class="h3 fw-bold mb-0 text-white">{{ $tenant->users_count }}</div>
                        <div class="text-success small mt-2"><i class="bi bi-arrow-up"></i> 12% increase</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-2 text-uppercase fw-bold">Total Orders</div>
                        <div class="h3 fw-bold mb-0 text-white">{{ $tenant->orders_count }}</div>
                        <div class="text-primary small mt-2">Last 30 days</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-2 text-uppercase fw-bold">Wallet Balances</div>
                        <div class="h3 fw-bold mb-0 text-white">${{ number_format($tenant->wallets()->sum('balance'), 2) }}</div>
                        <div class="text-muted small mt-2">Across all users</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-2 text-uppercase fw-bold">API Status</div>
                        <div class="h3 fw-bold mb-0 text-success">Healthy</div>
                        <div class="text-muted small mt-2">Last check 5m ago</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Order Trends -->
            <div class="col-lg-8">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                        <h5 class="card-title mb-0">Order Activity</h5>
                    </div>
                    <div class="card-body p-4">
                        <div style="height: 300px;" class="d-flex align-items-center justify-content-center bg-secondary bg-opacity-5 rounded-3">
                            <span class="text-muted small">Activity Chart Placeholder (Requires Chart.js)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="col-lg-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                        <h5 class="card-title mb-0">Recent Users</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($tenant->users()->latest()->limit(5)->get() as $user)
                                <li class="list-group-item bg-transparent border-secondary border-opacity-10 px-4 py-3 d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-secondary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-white small">{{ $user->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $user->email }}</div>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small" style="font-size: 0.6rem;">Active</span>
                                </li>
                            @empty
                                <li class="list-group-item bg-transparent text-center py-4 text-muted small">No users found</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 p-4 text-center">
                        <a href="{{ route('admin.users.index', ['tenant' => $tenant->uid]) }}" class="small text-primary text-decoration-none">View all users <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
