@extends('layouts.master')

@section('title', 'Sub-accounts')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">Sub-<span class="text-primary">Accounts</span></h2>
                <p class="text-muted small">Manage team members and API sub-users under your tenant.</p>
            </div>
            <button class="btn btn-primary px-4 py-2 rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#createSubaccountModal">
                <i class="bi bi-person-plus me-2"></i> Add Sub-account
            </button>
        </div>

        <!-- Sub-accounts List -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="bg-secondary bg-opacity-5">
                            <tr>
                                <th class="px-4 py-3 border-secondary border-opacity-10 small text-uppercase">User</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Role</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">API Key</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Orders</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Status</th>
                                <th class="px-4 py-3 border-secondary border-opacity-10 text-end small text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-3" colspan="6">
                                    <div class="text-center py-5">
                                        <i class="bi bi-people fs-1 text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-0">No sub-accounts created yet.</p>
                                        <button class="btn btn-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#createSubaccountModal">
                                            <i class="bi bi-plus-circle me-2"></i> Create First Sub-account
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Sub-account Modal -->
<div class="modal fade" id="createSubaccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-surface border-secondary border-opacity-10">
            <div class="modal-header border-secondary border-opacity-10">
                <h5 class="modal-title fw-bold">Create Sub-account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Name</label>
                        <input type="text" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Email</label>
                        <input type="email" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="john@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Role</label>
                        <select class="form-select bg-dark border-secondary border-opacity-20 text-white">
                            <option value="member">Member (Can purchase numbers)</option>
                            <option value="api_user">API User (API access only)</option>
                            <option value="manager">Manager (Can manage team)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Initial Wallet Balance</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary border-opacity-20 text-muted">$</span>
                            <input type="number" step="0.01" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="0.00" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-10">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-2"></i> Create Sub-account
                    </button>
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
