@extends('layouts.master')

@section('title', 'Earnings Report')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">Earnings Report</h2>
                <p class="text-muted small">Detailed breakdown of platform revenue and transaction history.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-filter me-2"></i> Filter
                </button>
                <a href="{{ route('admin.reports.export', ['type' => 'earnings']) }}" class="btn btn-primary rounded-3">
                    <i class="bi bi-download me-2"></i> Export CSV
                </a>
            </div>
        </div>

        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="bg-secondary bg-opacity-5">
                            <tr>
                                <th class="px-4 py-3 small text-uppercase text-secondary">Transaction ID</th>
                                <th class="py-3 small text-uppercase text-secondary">Tenant</th>
                                <th class="py-3 small text-uppercase text-secondary">Amount</th>
                                <th class="py-3 small text-uppercase text-secondary">Type</th>
                                <th class="py-3 small text-uppercase text-secondary">Status</th>
                                <th class="px-4 py-3 text-end small text-uppercase text-secondary">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($earnings as $tx)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-white small">#{{ $tx->id }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-white small">{{ $tx->wallet->tenant->name ?? 'System' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-success fw-bold small">GHS {{ number_format($tx->amount, 2) }}</div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded small" style="font-size: 0.65rem;">{{ strtoupper($tx->type) }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded small" style="font-size: 0.65rem;">{{ strtoupper($tx->status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-end text-muted small">
                                        {{ $tx->created_at->format('M d, Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted small">No earnings data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top border-secondary border-opacity-10">
                    {{ $earnings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
