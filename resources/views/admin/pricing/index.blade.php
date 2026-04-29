@extends('layouts.master')

@section('title', 'Pricing Management')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">Pricing <span class="text-primary">Management</span></h2>
                <p class="text-muted small">Configure markup rules and pricing strategies for services.</p>
            </div>
            <button class="btn btn-primary px-4 py-2 rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addPricingRuleModal">
                <i class="bi bi-plus-circle me-2"></i> Add Rule
            </button>
        </div>
    </div>

    <!-- Global Pricing Settings -->
    <div class="col-lg-6">
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-gear me-2 text-primary"></i> Global Settings</h6>
            </div>
            <div class="card-body p-4">
                @php
                    $tenant = \App\Models\Tenant::where('is_default', true)->first();
                    $settings = $tenant->settings['pricing'] ?? [];
                @endphp
                <form action="{{ route('admin.pricing.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Default Markup ($)</label>
                        <input type="number" step="0.01" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                               value="{{ $settings['markup'] ?? '0.50' }}" name="markup" required>
                        <div class="form-text text-muted small">Base markup applied to all services</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Demand Adjustment ($)</label>
                        <input type="number" step="0.01" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                               value="{{ $settings['demand_adjustment'] ?? '0.00' }}" name="demand_adjustment">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Risk Factor ($)</label>
                        <input type="number" step="0.01" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                               value="{{ $settings['risk_factor'] ?? '0.00' }}" name="risk_factor">
                    </div>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-2"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Per-Service Pricing -->
    <div class="col-lg-6">
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2 text-primary"></i> Per-Service Markup</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="bg-secondary bg-opacity-5">
                            <tr>
                                <th class="px-4 py-3 border-secondary border-opacity-10 small">Service</th>
                                <th class="py-3 border-secondary border-opacity-10 small">Markup</th>
                                <th class="px-4 py-3 border-secondary border-opacity-10 text-end small">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-white">WhatsApp</div>
                                    <div class="text-muted small">Service ID: 1</div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">$0.75</span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button class="btn btn-icon btn-sm text-secondary p-0"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-icon btn-sm text-danger p-0 ms-2"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-white">Telegram</div>
                                    <div class="text-muted small">Service ID: 2</div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">$0.50</span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button class="btn btn-icon btn-sm text-secondary p-0"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-icon btn-sm text-danger p-0 ms-2"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tenant-Specific Pricing -->
    <div class="col-12">
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i> Tenant-Specific Pricing</h6>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-20 text-info rounded-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Tenants can have custom pricing rules that override global settings. Configure per-tenant markup in the tenant edit page.
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="bg-secondary bg-opacity-5">
                            <tr>
                                <th class="px-4 py-3 border-secondary border-opacity-10 small">Tenant</th>
                                <th class="py-3 border-secondary border-opacity-10 small">Custom Markup</th>
                                <th class="py-3 border-secondary border-opacity-10 small">Status</th>
                                <th class="px-4 py-3 border-secondary border-opacity-10 text-end small">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Tenant::all() as $tenant)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-white">{{ $tenant->name }}</div>
                                        <div class="text-muted small">{{ $tenant->domain }}</div>
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $pricing = $tenant->settings['pricing'] ?? [];
                                            $markup = $pricing['markup'] ?? 'Default';
                                        @endphp
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                                            {{ is_numeric($markup) ? '$' . number_format($markup, 2) : $markup }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $tenant->status === 'active' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $tenant->status === 'active' ? 'success' : 'danger' }} px-3 py-1 rounded-pill small">
                                            {{ ucfirst($tenant->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <a href="#" class="btn btn-sm btn-secondary px-3 rounded-3">Edit Pricing</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Pricing Rule Modal -->
<div class="modal fade" id="addPricingRuleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-surface border-secondary border-opacity-10">
            <form>
                <div class="modal-header border-secondary border-opacity-10">
                    <h5 class="modal-title fw-bold">Add Pricing Rule</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Service</label>
                        <select class="form-select bg-dark border-secondary border-opacity-20 text-white">
                            <option selected disabled>Select service...</option>
                            <option>WhatsApp</option>
                            <option>Telegram</option>
                            <option>Instagram</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Markup ($)</label>
                        <input type="number" step="0.01" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                               placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Applies To</label>
                        <select class="form-select bg-dark border-secondary border-opacity-20 text-white">
                            <option value="all">All Tenants</option>
                            <option value="specific">Specific Tenant</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-10">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Add Rule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
    .modal-content { background-color: var(--surface-dark) !important; }
</style>
@endsection
