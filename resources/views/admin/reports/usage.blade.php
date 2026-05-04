@extends('layouts.master')

@section('title', 'Usage Report')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">Usage Report</h2>
                <p class="text-muted small">Platform utilization metrics including order volume and service demand.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-filter me-2"></i> Filter
                </button>
                <a href="{{ route('admin.reports.export', ['type' => 'usage']) }}" class="btn btn-primary rounded-3">
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
                                <th class="px-4 py-3 small text-uppercase text-secondary">Order ID</th>
                                <th class="py-3 small text-uppercase text-secondary">User</th>
                                <th class="py-3 small text-uppercase text-secondary">Service</th>
                                <th class="py-3 small text-uppercase text-secondary">Country</th>
                                <th class="py-3 small text-uppercase text-secondary">Cost/Price</th>
                                <th class="py-3 small text-uppercase text-secondary">Status</th>
                                <th class="px-4 py-3 text-end small text-uppercase text-secondary">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-white small">{{ $order->uid }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Tenant: {{ $order->tenant->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-white small">{{ $order->user->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-white small">{{ $order->service->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-white small">{{ $order->country->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="small">
                                            <span class="text-muted">C:</span> <span class="text-danger">${{ number_format($order->cost, 2) }}</span><br>
                                            <span class="text-muted">P:</span> <span class="text-success">${{ number_format($order->price, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }} bg-opacity-10 text-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }} px-2 py-1 rounded small" style="font-size: 0.65rem;">{{ strtoupper($order->status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-end text-muted small">
                                        {{ $order->created_at->format('M d, Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted small">No usage data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top border-secondary border-opacity-10">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
