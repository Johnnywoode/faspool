@extends('layouts.master')

@section('title', 'Active Numbers')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">Active Numbers</h2>
                <p class="text-muted small">Manage all active virtual number rentals across the platform.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-filter me-2"></i> All Tenants
                </button>
            </div>
        </div>

        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="bg-secondary bg-opacity-5">
                            <tr>
                                <th class="px-4 py-3 small text-uppercase text-secondary">Number</th>
                                <th class="py-3 small text-uppercase text-secondary">Service/User</th>
                                <th class="py-3 small text-uppercase text-secondary">Tenant</th>
                                <th class="py-3 small text-uppercase text-secondary">Expires</th>
                                <th class="px-4 py-3 text-end small text-uppercase text-secondary">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-white">{{ $order->number }}</div>
                                        <div class="text-muted small">{{ $order->uid }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-white small">{{ $order->service->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $order->user->name }}</div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small">{{ $order->tenant->name }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-warning small fw-bold">
                                            <i class="bi bi-clock me-1"></i> {{ $order->expires_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end bg-surface border-secondary border-opacity-10 shadow">
                                                <li>
                                                    <form action="{{ route('admin.numbers.release', $order) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger small"><i class="bi bi-unlock me-2"></i> Release Number</button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider border-secondary border-opacity-10"></li>
                                                <li>
                                                    <a class="dropdown-item text-white small" href="#" onclick="showExtendModal('{{ $order->uid }}')"><i class="bi bi-plus-circle me-2"></i> Extend Rental</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">No active numbers found</td>
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

<!-- Extend Modal (Simple Placeholder) -->
<div class="modal fade" id="extendModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-surface border-secondary border-opacity-10 rounded-4">
            <div class="modal-header border-secondary border-opacity-10">
                <h5 class="modal-title text-white">Extend Rental</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="extendForm" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label small text-secondary fw-bold text-uppercase">Minutes to Add</label>
                    <input type="number" name="minutes" class="form-control" value="15" min="1">
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Extend</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showExtendModal(uid) {
        const form = document.getElementById('extendForm');
        form.action = `/admin/numbers/${uid}/extend`;
        new bootstrap.Modal(document.getElementById('extendModal')).show();
    }
</script>
@endpush
