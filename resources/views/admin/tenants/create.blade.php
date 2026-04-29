@extends('layouts.master')

@section('title', __('admin.tenants.create_title'))

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">{{ __('admin.tenants.create_title') }}</h2>
                <p class="text-muted small">{{ __('admin.tenants.create_subtitle') }}</p>
            </div>
            <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-bold">
                <i class="bi bi-arrow-left me-2"></i> {{ __('admin.tenants.back') }}
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.tenants.store') }}" method="POST">
                    @csrf
                    
                    <h5 class="fw-bold text-white mb-4"><i class="bi bi-building me-2 text-primary"></i> {{ __('admin.tenants.details') }}</h5>
                    
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small text-uppercase">{{ __('admin.tenants.workspace_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-dark border-secondary border-opacity-20 text-white @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small text-uppercase">{{ __('admin.tenants.custom_domain') }}</label>
                            <input type="text" name="domain" class="form-control bg-dark border-secondary border-opacity-20 text-white @error('domain') is-invalid @enderror" value="{{ old('domain') }}">
                            <div class="form-text text-muted small">{{ __('admin.tenants.domain_help') }}</div>
                            @error('domain') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold text-white mb-4"><i class="bi bi-person-badge me-2 text-primary"></i> {{ __('admin.tenants.admin_details') }}</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label text-secondary small text-uppercase">{{ __('admin.tenants.admin_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="admin_name" class="form-control bg-dark border-secondary border-opacity-20 text-white @error('admin_name') is-invalid @enderror" value="{{ old('admin_name') }}" required>
                            @error('admin_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small text-uppercase">{{ __('admin.tenants.admin_email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="admin_email" class="form-control bg-dark border-secondary border-opacity-20 text-white @error('admin_email') is-invalid @enderror" value="{{ old('admin_email') }}" required>
                            @error('admin_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small text-uppercase">{{ __('admin.tenants.admin_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="admin_password" class="form-control bg-dark border-secondary border-opacity-20 text-white @error('admin_password') is-invalid @enderror" required minlength="8">
                            @error('admin_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="border-secondary border-opacity-20 my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold">
                            {{ __('admin.tenants.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

