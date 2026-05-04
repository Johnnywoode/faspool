@extends('layouts.master')

@section('title', 'Long-term Rentals')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-10">
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold h3 mb-1">Long-term <span class="text-primary">Rentals</span></h2>
                <p class="text-muted">Rent a dedicated virtual number for extended periods (up to 30 days).</p>
            </div>
            <button class="btn btn-primary px-4 py-2 rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#newRentalModal">
                <i class="bi bi-plus-lg me-2"></i> New Rental
            </button>
        </div>

        <!-- Active Rentals -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Your Active Rentals</h6>
            </div>
            <div class="card-body p-0">
                @if($rentals->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">No active long-term rentals found.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-secondary bg-opacity-5">
                                <tr>
                                    <th class="px-4 py-3 small text-uppercase">Number</th>
                                    <th class="py-3 small text-uppercase">Service</th>
                                    <th class="py-3 small text-uppercase">Country</th>
                                    <th class="py-3 small text-uppercase">Expires In</th>
                                    <th class="px-4 py-3 text-end small text-uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rentals as $rental)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold text-white small">{{ $rental->number }}</div>
                                            <div class="text-muted small" style="font-size: 0.7rem;">UID: {{ $rental->uid }}</div>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-white small">{{ $rental->service->name }}</div>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-white small">{{ $rental->country->name }} {{ $rental->country->flag }}</div>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $daysLeft = now()->diffInDays($rental->expires_at, false);
                                            @endphp
                                            <span class="badge bg-{{ $daysLeft < 3 ? 'danger' : 'success' }} bg-opacity-10 text-{{ $daysLeft < 3 ? 'danger' : 'success' }} px-2 py-1 rounded small">
                                                {{ $daysLeft > 0 ? $daysLeft . ' Days' : 'Expiring Soon' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="showExtendModal('{{ $rental->uid }}')">
                                                Extend
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top border-secondary border-opacity-10">
                        {{ $rentals->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- New Rental Modal -->
<div class="modal fade" id="newRentalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-surface border-secondary border-opacity-10 rounded-4">
            <div class="modal-header border-secondary border-opacity-10">
                <h5 class="modal-title text-white">Start New Rental</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('rentals.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-secondary fw-bold text-uppercase">Select Service</label>
                        <select name="service_id" class="form-select bg-dark border-secondary border-opacity-20 text-white" required>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary fw-bold text-uppercase">Select Country</label>
                        <select name="country_id" class="form-select bg-dark border-secondary border-opacity-20 text-white" required>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small text-secondary fw-bold text-uppercase">Duration (Days)</label>
                        <input type="number" name="duration" class="form-control bg-dark border-secondary border-opacity-20 text-white" value="7" min="1" max="30" required>
                        <div class="form-text small text-muted">Cost: GHS 5.00 per day.</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-lg">Purchase Rental</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Extend Modal -->
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
                    <label class="form-label small text-secondary fw-bold text-uppercase">Days to Add</label>
                    <input type="number" name="days" class="form-control" value="7" min="1" required>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Extend</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
</style>

<script>
    function showExtendModal(uid) {
        const form = document.getElementById('extendForm');
        form.action = `/rentals/${uid}/extend`;
        new bootstrap.Modal(document.getElementById('extendModal')).show();
    }
</script>
@endsection
