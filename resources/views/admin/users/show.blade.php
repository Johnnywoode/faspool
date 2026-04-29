@extends('layouts.master')

@section('title', 'User Details')

@section('content')
<div class="row g-4">
    <div class="col-xl-4">
        <!-- User Profile Card -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-body p-4 text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff&size=128" 
                     class="rounded-circle border border-secondary border-opacity-20 mb-3" width="100">
                <h4 class="fw-bold text-white mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">{{ ucfirst($role->name) }}</span>
                    @endforeach
                    @if($user->is_banned)
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Banned</span>
                    @endif
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm px-3 rounded-3">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.users.toggle-ban', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm px-3 rounded-3">
                            <i class="bi bi-slash-circle me-1"></i> {{ $user->is_banned ? 'Unban' : 'Ban' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Wallet Info -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm mt-4">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-wallet2 me-2 text-primary"></i> Wallet</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="text-muted small mb-1">Balance</div>
                    <div class="h3 fw-bold text-success mb-0">${{ number_format($user->wallet->balance ?? 0, 2) }}</div>
                </div>
                <button class="btn btn-sm btn-primary w-100 rounded-3" data-bs-toggle="modal" data-bs-target="#adjustWalletModal">
                    <i class="bi bi-plus-circle me-1"></i> Adjust Balance
                </button>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- User Info -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i> User Information</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Tenant</div>
                        <div class="text-white">{{ $user->tenant->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Joined</div>
                        <div class="text-white">{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Total Orders</div>
                        <div class="text-white">{{ $user->orders->count() }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Email Verified</div>
                        <div class="text-white">{{ $user->email_verified_at ? 'Yes' : 'No' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i> Recent Orders</h6>
            </div>
            <div class="card-body p-0">
                @if($user->orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-secondary bg-opacity-5">
                                <tr>
                                    <th class="px-4 py-3 border-secondary border-opacity-10 small">Service</th>
                                    <th class="py-3 border-secondary border-opacity-10 small">Number</th>
                                    <th class="py-3 border-secondary border-opacity-10 small">Status</th>
                                    <th class="py-3 border-secondary border-opacity-10 small">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->orders->take(10) as $order)
                                    <tr>
                                        <td class="px-4 py-3 text-white">{{ $order->service->name ?? 'N/A' }}</td>
                                        <td class="py-3 text-secondary">{{ $order->number }}</td>
                                        <td class="py-3">
                                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'waiting_sms' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $order->status === 'completed' ? 'success' : ($order->status === 'waiting_sms' ? 'warning' : 'danger') }} px-2 py-1 rounded-pill small">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 fw-bold text-white">${{ number_format($order->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">No orders yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Adjust Wallet Modal -->
<div class="modal fade" id="adjustWalletModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-surface border-secondary border-opacity-10">
            <form action="{{ route('admin.users.adjust-wallet', $user->id) }}" method="POST">
                @csrf
                <div class="modal-header border-secondary border-opacity-10">
                    <h5 class="modal-title fw-bold">Adjust Wallet Balance</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Type</label>
                        <select name="type" class="form-select bg-dark border-secondary border-opacity-20 text-white" required>
                            <option value="credit">Credit (Add funds)</option>
                            <option value="debit">Debit (Remove funds)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Amount ($)</label>
                        <input type="number" step="0.01" name="amount" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                               placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted text-uppercase">Description</label>
                        <input type="text" name="description" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                               placeholder="Reason for adjustment" required>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-10">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Adjust</button>
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
