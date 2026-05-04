@extends('layouts.master')

@section('title', 'Bulk Operations')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">Bulk Operations</h2>
                <p class="text-muted small">Perform batch updates and system-wide maintenance tasks.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Provider Import -->
            <div class="col-md-6">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4 d-flex align-items-center">
                        <div class="p-2 bg-primary bg-opacity-10 rounded-3 me-3">
                            <i class="ph ph-upload text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0 text-white">Import Numbers</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Fetch and update service inventory and pricing from connected SMS providers.</p>
                        <form action="{{ route('admin.bulk.import-numbers') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small text-secondary fw-bold text-uppercase">Select Provider</label>
                                <select name="provider_id" class="form-select">
                                    @foreach(\App\Models\Provider::all() as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Start Import Process</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bulk Refund -->
            <div class="col-md-6">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4 d-flex align-items-center">
                        <div class="p-2 bg-success bg-opacity-10 rounded-3 me-3">
                            <i class="ph ph-currency-dollar text-success fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0 text-white">Bulk Refund</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Automatically refund users for failed or cancelled orders that were debited.</p>
                        <form action="{{ route('admin.bulk.refund') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small text-secondary fw-bold text-uppercase">Time Range</label>
                                <select name="range" class="form-select">
                                    <option value="today">Last 24 Hours</option>
                                    <option value="week">Last 7 Days</option>
                                    <option value="all">All Pending Refunds</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Process Bulk Refunds</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bulk Cancellation -->
            <div class="col-12">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4 d-flex align-items-center">
                        <div class="p-2 bg-danger bg-opacity-10 rounded-3 me-3">
                            <i class="ph ph-x-circle text-danger fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0 text-white">Bulk Cancellation</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <p class="text-muted small mb-4">Cancel all orders that have been pending/waiting for more than 20 minutes.</p>
                        <form action="{{ route('admin.bulk.cancel-orders') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="stale">
                            <button type="submit" class="btn btn-outline-danger px-5 py-2 fw-bold" onclick="return confirm('This will cancel all stale orders. Proceed?')">Cancel All Stale Orders</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
