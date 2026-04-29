@extends('layouts.master')

@section('title', 'Rental Numbers')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">Rental <span class="text-primary">Numbers</span></h2>
                <p class="text-muted small">Rent virtual numbers for extended periods with auto-reception.</p>
            </div>
            <button class="btn btn-primary px-4 py-2 rounded-3 fw-bold">
                <i class="bi bi-plus-circle me-2"></i> New Rental
            </button>
        </div>

        <!-- Active Rentals -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm mb-4">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2 text-primary"></i> Active Rentals</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="bg-secondary bg-opacity-5">
                            <tr>
                                <th class="px-4 py-3 border-secondary border-opacity-10 small text-uppercase">Number</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Service</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Country</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Expires</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">SMS Received</th>
                                <th class="px-4 py-3 border-secondary border-opacity-10 text-end small text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box bg-secondary bg-opacity-10 rounded-3 p-2">
                                            <i class="bi bi-phone fs-5"></i>
                                        </div>
                                        <span class="fw-bold text-white">+1 (555) 123-4567</span>
                                    </div>
                                </td>
                                <td class="py-3 text-secondary">WhatsApp</td>
                                <td class="py-3 text-secondary">🇺🇸 United States</td>
                                <td class="py-3"><span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill small">2 days left</span></td>
                                <td class="py-3"><span class="fw-bold text-white">3</span></td>
                                <td class="px-4 py-3 text-end">
                                    <button class="btn btn-icon btn-sm text-primary p-0 me-2"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-icon btn-sm text-danger p-0"><i class="bi bi-x-circle"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Available Services for Rent -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i> Rent a Number</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label small text-muted text-uppercase">Select Service</label>
                        <select class="form-select bg-dark border-secondary border-opacity-20 text-white">
                            <option selected disabled>Choose a service...</option>
                            <option>WhatsApp</option>
                            <option>Telegram</option>
                            <option>Instagram</option>
                            <option>Facebook</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted text-uppercase">Select Country</label>
                        <select class="form-select bg-dark border-secondary border-opacity-20 text-white">
                            <option selected disabled>Choose a country...</option>
                            <option>🇺🇸 United States</option>
                            <option>🇬🇧 United Kingdom</option>
                            <option>🇩🇪 Germany</option>
                            <option>🇫🇷 France</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted text-uppercase">Rental Duration</label>
                        <select class="form-select bg-dark border-secondary border-opacity-20 text-white">
                            <option value="1">1 Day - $5.00</option>
                            <option value="7">7 Days - $25.00</option>
                            <option value="30">30 Days - $80.00</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button class="btn btn-primary w-100 py-2 rounded-3 fw-bold">
                            Rent Number <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
    .icon-box { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
</style>
@endsection
