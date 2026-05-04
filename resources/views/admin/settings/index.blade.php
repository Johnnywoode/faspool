@extends('layouts.master')

@section('title', 'System Settings')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">System Settings</h2>
                <p class="text-muted small">Configure global platform behavior, integrations, and security.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.system-info') }}" class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-info-circle me-2"></i> System Info
                </a>
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-3">
                        <i class="bi bi-trash me-2"></i> Clear Cache
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Navigation Tabs -->
            <div class="col-md-3">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-body p-2">
                        <div class="nav flex-column nav-pills" id="settings-tabs" role="tablist">
                            <button class="nav-link active text-start mb-1 rounded-3 py-2 px-3" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                                <i class="ph ph-gear me-2"></i> General
                            </button>
                            <button class="nav-link text-start mb-1 rounded-3 py-2 px-3" id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment" type="button" role="tab">
                                <i class="ph ph-credit-card me-2"></i> Payment
                            </button>
                            <button class="nav-link text-start mb-1 rounded-3 py-2 px-3" id="notification-tab" data-bs-toggle="pill" data-bs-target="#notification" type="button" role="tab">
                                <i class="ph ph-bell me-2"></i> Notifications
                            </button>
                            <button class="nav-link text-start mb-1 rounded-3 py-2 px-3" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                                <i class="ph ph-shield-check me-2"></i> Security
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="col-md-9">
                <div class="tab-content" id="settings-tabContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                                <h5 class="card-title mb-0 text-white">General Settings</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.settings.update-general') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small text-secondary fw-bold text-uppercase">Site Name</label>
                                            <input type="text" name="site_name" class="form-control" value="{{ \App\Models\SystemSetting::get('site_name', config('app.name')) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-secondary fw-bold text-uppercase">Contact Email</label>
                                            <input type="email" name="contact_email" class="form-control" value="{{ \App\Models\SystemSetting::get('contact_email') }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small text-secondary fw-bold text-uppercase">Site Description</label>
                                            <textarea name="site_description" class="form-control" rows="3">{{ \App\Models\SystemSetting::get('site_description') }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="impersonation_enabled" value="1" id="impersonationToggle" {{ \App\Models\SystemSetting::get('impersonation_enabled', true) ? 'checked' : '' }}>
                                                <label class="form-check-label text-white" for="impersonationToggle">Enable User Impersonation</label>
                                                <div class="form-text small text-muted">Allows admins to log in as users without a password for support.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-primary px-4">Save General Settings</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Settings -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                                <h5 class="card-title mb-0 text-white">Payment Settings</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.settings.update-payment') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label small text-secondary fw-bold text-uppercase">Payment Markup (%)</label>
                                            <input type="number" step="0.01" name="payment_markup" class="form-control" value="{{ \App\Models\SystemSetting::get('payment_markup', 0) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-secondary fw-bold text-uppercase">Min Deposit (GHS)</label>
                                            <input type="number" name="min_deposit" class="form-control" value="{{ \App\Models\SystemSetting::get('min_deposit', 10) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-secondary fw-bold text-uppercase">Max Deposit (GHS)</label>
                                            <input type="number" name="max_deposit" class="form-control" value="{{ \App\Models\SystemSetting::get('max_deposit', 1000) }}">
                                        </div>
                                    </div>
                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-primary px-4">Save Payment Settings</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification & Security placehoders... -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .nav-pills .nav-link {
        color: #94a3b8;
        background: transparent;
        transition: 0.2s;
    }
    .nav-pills .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
    }
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary) !important;
        color: #fff !important;
    }
</style>
@endpush
