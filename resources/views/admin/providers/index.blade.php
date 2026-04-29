@extends('layouts.master')

@section('title', __('menu.providers'))

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">SMS Providers</h2>
                <p class="text-muted small">Configure and monitor external SMS API integrations.</p>
            </div>
            <button class="btn btn-primary px-4 py-2 rounded-3 fw-bold">
                <i class="bi bi-plus-lg me-2"></i> Add Provider
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="row g-4">
            @foreach($providers as $provider)
                <div class="col-md-6 col-xl-4">
                    <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                    <i class="bi bi-hdd-network fs-3"></i>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" {{ $provider->is_active ? 'checked' : '' }}>
                                </div>
                            </div>
                            <h5 class="fw-bold text-white mb-1">{{ $provider->name }}</h5>
                            <p class="text-muted small mb-4">{{ $provider->adapter }}</p>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-secondary flex-grow-1 py-2 rounded-3 small" data-bs-toggle="modal" data-bs-target="#configModal{{ $provider->id }}">Configuration</button>
                                <button class="btn btn-outline-secondary py-2 px-3 rounded-3"><i class="bi bi-arrow-repeat"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Config Modal -->
                <div class="modal fade" id="configModal{{ $provider->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark border-secondary border-opacity-20 text-white rounded-4">
                            <form action="{{ route('admin.providers.update', $provider->id) }}" method="POST">
                                @csrf
                                <div class="modal-header border-secondary border-opacity-10 py-3 px-4">
                                    <h5 class="modal-title fw-bold">Configure {{ $provider->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label text-secondary small text-uppercase">API Key</label>
                                        <input type="text" name="api_key" class="form-control bg-dark border-secondary border-opacity-20 text-white" value="{{ $provider->config['api_key'] ?? '' }}" placeholder="Enter API Key" required>
                                    </div>
                                    <!-- Add more config fields here if needed in the future -->
                                </div>
                                <div class="modal-footer border-secondary border-opacity-10 py-3 px-4">
                                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary rounded-3">Save Configuration</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
