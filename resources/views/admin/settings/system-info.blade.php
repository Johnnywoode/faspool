@extends('layouts.master')

@section('title', 'System Information')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active">System Info</li>
                    </ol>
                </nav>
                <h2 class="fw-bold h3 mb-0">System Information</h2>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-2"></i> Back to Settings
            </a>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                        <h5 class="card-title mb-0 text-white">Environment Details</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($info as $key => $value)
                                <li class="list-group-item bg-transparent border-secondary border-opacity-10 px-4 py-3 d-flex justify-content-between align-items-center">
                                    <span class="text-secondary small text-uppercase fw-bold">{{ str_replace('_', ' ', $key) }}</span>
                                    <span class="text-white fw-medium">{{ $value }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                        <h5 class="card-title mb-0 text-white">Application Resources</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-secondary bg-opacity-5 rounded-3">
                                    <div class="h4 fw-bold text-white mb-1">{{ \App\Models\User::count() }}</div>
                                    <div class="text-muted small">Total Users</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-secondary bg-opacity-5 rounded-3">
                                    <div class="h4 fw-bold text-white mb-1">{{ \App\Models\Tenant::count() }}</div>
                                    <div class="text-muted small">Total Tenants</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-secondary bg-opacity-5 rounded-3">
                                    <div class="h4 fw-bold text-white mb-1">{{ \App\Models\Order::count() }}</div>
                                    <div class="text-muted small">Total Orders</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-secondary bg-opacity-5 rounded-3">
                                    <div class="h4 fw-bold text-white mb-1">{{ \App\Models\Transaction::count() }}</div>
                                    <div class="text-muted small">Transactions</div>
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
