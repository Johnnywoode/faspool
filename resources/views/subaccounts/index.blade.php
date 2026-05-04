@extends('layouts.master')

@section('title', 'Sub-accounts')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-10">
        <div class="mb-4">
            <h2 class="fw-bold h3 mb-1">Sub-<span class="text-primary">accounts</span></h2>
            <p class="text-muted">Create and manage accounts for your team or separate projects.</p>
        </div>

        <div class="row g-4 mb-4">
            <!-- Create Sub-account -->
            <div class="col-lg-5">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i> Create Sub-account</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('subaccounts.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="Account name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="email@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Password</label>
                                <input type="password" name="password" class="form-control bg-dark border-secondary border-opacity-20 text-white" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small text-muted text-uppercase fw-bold">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary border-opacity-20 text-white" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold">
                                Create Account <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Manage Sub-accounts -->
            <div class="col-lg-7">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i> Your Accounts</h6>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-pill small">{{ $subAccounts->total() }} Accounts</span>
                    </div>
                    <div class="card-body p-0">
                        @if($subAccounts->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-person-workspace fs-1 text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">No sub-accounts created yet.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 align-middle">
                                    <thead class="bg-secondary bg-opacity-5">
                                        <tr>
                                            <th class="px-4 py-3 small text-uppercase">Account</th>
                                            <th class="py-3 small text-uppercase">Balance</th>
                                            <th class="px-4 py-3 text-end small text-uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subAccounts as $acc)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="fw-bold text-white small">{{ $acc->name }}</div>
                                                    <div class="text-muted small" style="font-size: 0.7rem;">{{ $acc->email }}</div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="text-success fw-bold small">GHS {{ number_format($acc->wallet->balance ?? 0, 2) }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-end">
                                                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="showTransferModal('{{ $acc->uid }}', '{{ $acc->name }}')">
                                                        Transfer Fund
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 border-top border-secondary border-opacity-10">
                                {{ $subAccounts->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-surface border-secondary border-opacity-10 rounded-4">
            <div class="modal-header border-secondary border-opacity-10">
                <h5 class="modal-title text-white">Transfer Funds</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="transferForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">Transfer balance to <strong class="text-white" id="transferTargetName">Account</strong></p>
                    <label class="form-label small text-secondary fw-bold text-uppercase">Amount (GHS)</label>
                    <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" min="1" required>
                    <div class="form-text small mt-2">Available Balance: GHS {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Transfer Now</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTransferModal(uid, name) {
        const form = document.getElementById('transferForm');
        form.action = `/subaccounts/${uid}/transfer`;
        document.getElementById('transferTargetName').innerText = name;
        new bootstrap.Modal(document.getElementById('transferModal')).show();
    }
</script>
@endpush

<style>
    .bg-surface { background-color: var(--surface-dark); }
</style>
@endsection
