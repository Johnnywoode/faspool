@extends('layouts.master')

@section('title', __('menu.deposit'))

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-10">
        <div class="mb-4">
            <h2 class="fw-bold h3 mb-1">{{ __('menu.deposit') }} <span class="text-primary">Funds</span></h2>
            <p class="text-muted">Top up your balance using our secure payment gateways.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <div class="row g-4">
            <!-- Dynamic Gateways -->
            @forelse($gateways as $gateway)
                <div class="col-md-6">
                    <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100 p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-credit-card fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-white mb-0">{{ $gateway->name }}</h5>
                                <p class="text-muted small mb-0">Secure payment via {{ $gateway->name }}</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('wallet.payment.init', ['gateway' => $gateway->slug]) }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-dark border-secondary border-opacity-20 text-muted">$</span>
                                <input type="number" name="amount" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="Amount" min="1" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold">Pay with {{ $gateway->name }}</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">No payment gateways configured.</p>
                </div>
            @endforelse

            <!-- Balance History -->
            <div class="col-12 mt-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm">
                    <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                        <h6 class="mb-0 fw-bold">Recent Transactions</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="small text-uppercase text-secondary">
                                    <tr>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="py-3">Method</th>
                                        <th class="py-3">Amount</th>
                                        <th class="py-3">Status</th>
                                        <th class="px-4 py-3 text-end">ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $tx)
                                        <tr>
                                            <td class="px-4 py-3 small text-muted">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                                            <td class="py-3 text-white">{{ ucfirst($tx->meta['gateway'] ?? 'system') }}</td>
                                            <td class="py-3 fw-bold {{ $tx->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                                {{ $tx->type === 'credit' ? '+' : '-' }}${{ number_format($tx->amount, 2) }}
                                            </td>
                                            <td class="py-3">
                                                @if($tx->status === 'completed')
                                                    <span class="badge bg-success bg-opacity-10 text-success">Completed</span>
                                                @elseif($tx->status === 'pending')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger">Failed</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-end small text-muted font-monospace">{{ substr($tx->id, 0, 8) }}...</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted small">No transactions yet.</td>
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
