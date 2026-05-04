@extends('layouts.master')

@section('title', 'Edit Tenant: ' . $tenant->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}" class="text-decoration-none">Tenants</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tenants.show', $tenant) }}" class="text-decoration-none">{{ $tenant->name }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
        <h1 class="h4 fw-bold mb-0">Edit Tenant</h1>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0" style="background:var(--bs-secondary-bg)">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Tenant Name <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $tenant->name) }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="domain" class="form-label fw-semibold">Domain</label>
                        <input
                            type="text"
                            id="domain"
                            name="domain"
                            class="form-control @error('domain') is-invalid @enderror"
                            placeholder="e.g. client.example.com"
                            value="{{ old('domain', $tenant->domain) }}"
                        >
                        @error('domain')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave blank to auto-generate from name.</div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(['active', 'inactive', 'suspended'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $tenant->status) === $s)>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2 me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
