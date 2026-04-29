@extends('layouts.master')

@section('title', 'Quick Order')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-10">
        <div class="mb-4">
            <h2 class="fw-bold h3 mb-1">Quick <span class="text-primary">Order</span></h2>
            <p class="text-muted">Select a service and country to receive your virtual number instantly.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <form action="{{ route('orders.purchase') }}" method="POST">
            @csrf
            <div class="row g-4">
                <!-- Service Selection -->
                <div class="col-md-6">
                    <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                        <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i> 1. Select Service</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-dark border-secondary border-opacity-20"><i class="bi bi-search"></i></span>
                                <input type="text" id="serviceSearch" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="Search service...">
                            </div>
                            <div class="service-list custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                                @foreach($services as $service)
                                    <label class="service-item d-flex align-items-center gap-3 p-3 rounded-4 mb-2 cursor-pointer border border-transparent transition">
                                        <input type="radio" name="service_id" value="{{ $service->id }}" class="d-none" required>
                                        <div class="icon-box bg-secondary bg-opacity-10 rounded-3 p-2">
                                            <i class="bi bi-phone fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-white">{{ $service->name }}</div>
                                            <div class="small text-muted">{{ $service->category ?? 'General' }}</div>
                                        </div>
                                        <i class="bi bi-check-circle-fill text-primary opacity-0 check-icon"></i>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Country Selection -->
                <div class="col-md-6">
                    <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                        <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-globe2 me-2 text-primary"></i> 2. Select Country</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-dark border-secondary border-opacity-20"><i class="bi bi-search"></i></span>
                                <input type="text" id="countrySearch" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="Search country...">
                            </div>
                            <div class="country-list custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                                @foreach($countries as $country)
                                    <label class="country-item d-flex align-items-center gap-3 p-3 rounded-4 mb-2 cursor-pointer border border-transparent transition">
                                        <input type="radio" name="country_id" value="{{ $country->id }}" class="d-none" required>
                                        <span class="fs-4">{{ $country->flag ?? '🏳️' }}</span>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-white">{{ $country->name }}</div>
                                            <div class="small text-muted">Reliability: 99%</div>
                                        </div>
                                        <i class="bi bi-check-circle-fill text-primary opacity-0 check-icon"></i>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary & Purchase -->
                <div class="col-12 mt-5">
                    <div class="card bg-primary bg-opacity-10 border border-primary border-opacity-20 rounded-5 p-4 p-md-5">
                        <div class="row align-items-center">
                            <div class="col-md-7 mb-4 mb-md-0">
                                <h3 class="fw-bold text-white mb-2">Ready to verify?</h3>
                                <p class="text-secondary mb-0">Select your preferred service and country above to see the final price. Our numbers are private and non-VoIP.</p>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <div class="d-inline-block text-start me-4 mb-3 mb-md-0">
                                    <div class="small text-secondary">Estimated Price</div>
                                    <div class="h3 fw-bold text-primary mb-0">$0.00</div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-4 shadow-lg fw-bold">
                                    Purchase Number <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
    .cursor-pointer { cursor: pointer; }
    .transition { transition: all 0.2s ease; }
    
    .service-item:hover, .country-item:hover {
        background: rgba(255, 255, 255, 0.03);
    }
    
    .service-item input:checked + .icon-box + div + .check-icon,
    .country-item input:checked + span + div + .check-icon {
        opacity: 1 !important;
    }
    
    .service-item input:checked + .icon-box + div,
    .country-item input:checked + span + div {
        color: var(--primary-accent) !important;
    }
    
    .service-item input:checked, .country-item input:checked {
        background: rgba(13, 110, 253, 0.05) !important;
        border-color: rgba(13, 110, 253, 0.3) !important;
    }

    /* Style for the actual checked label */
    .service-item:has(input:checked), .country-item:has(input:checked) {
        background: rgba(13, 110, 253, 0.08) !important;
        border-color: rgba(13, 110, 253, 0.4) !important;
    }
</style>

<script>
    $(document).ready(function() {
        const $serviceSearch = $('#serviceSearch');
        const $countrySearch = $('#countrySearch');
        const $serviceList = $('.service-list');
        const $countryList = $('.country-list');

        let debounceTimeout;

        const performSearch = () => {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                const serviceQ = $serviceSearch.val();
                const countryQ = $countrySearch.val();

                $.ajax({
                    url: `{{ route('orders.search') }}`,
                    type: 'GET',
                    data: {
                        service_q: serviceQ,
                        country_q: countryQ
                    },
                    dataType: 'json',
                    success: function(data) {
                        renderServices(data.services);
                        renderCountries(data.countries);
                    },
                    error: function(xhr, status, error) {
                        console.error('Search error:', error);
                    }
                });
            }, 300); // 300ms debounce
        };

        const renderServices = (services) => {
            $serviceList.empty();
            if(services.length === 0) {
                $serviceList.html('<div class="p-3 text-muted small text-center">No services found.</div>');
                return;
            }
            services.forEach(service => {
                const html = `
                    <label class="service-item d-flex align-items-center gap-3 p-3 rounded-4 mb-2 cursor-pointer border border-transparent transition">
                        <input type="radio" name="service_id" value="${service.id}" class="d-none" required>
                        <div class="icon-box bg-secondary bg-opacity-10 rounded-3 p-2">
                            <i class="bi bi-phone fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-white">${escapeHtml(service.name)}</div>
                            <div class="small text-muted">${escapeHtml(service.category ?? 'General')}</div>
                        </div>
                        <i class="bi bi-check-circle-fill text-primary opacity-0 check-icon"></i>
                    </label>
                `;
                $serviceList.append(html);
            });
        };

        const renderCountries = (countries) => {
            $countryList.empty();
            if(countries.length === 0) {
                $countryList.html('<div class="p-3 text-muted small text-center">No countries found.</div>');
                return;
            }
            countries.forEach(country => {
                const html = `
                    <label class="country-item d-flex align-items-center gap-3 p-3 rounded-4 mb-2 cursor-pointer border border-transparent transition">
                        <input type="radio" name="country_id" value="${country.id}" class="d-none" required>
                        <span class="fs-4">🏳️</span>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-white">${escapeHtml(country.name)}</div>
                            <div class="small text-muted">Reliability: 99%</div>
                        </div>
                        <i class="bi bi-check-circle-fill text-primary opacity-0 check-icon"></i>
                    </label>
                `;
                $countryList.append(html);
            });
        };

        const escapeHtml = (unsafe) => {
            return (unsafe || '').toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        };

        $serviceSearch.on('keyup', performSearch);
        $countrySearch.on('keyup', performSearch);
    });
</script>
@endsection
