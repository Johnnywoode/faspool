@extends('layouts.master')

@section('title', 'Edit Provider')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="mb-4">
            <h2 class="fw-bold h3 mb-1">Edit <span class="text-primary">Provider</span></h2>
            <p class="text-muted small">Update provider configuration.</p>
        </div>

        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.providers.update', $provider->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Name</label>
                            <input type="text" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                                   value="{{ $provider->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Slug</label>
                            <input type="text" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                                   value="{{ $provider->slug }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Adapter</label>
                            <input type="text" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                                   value="{{ $provider->adapter }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Status</label>
                            <select name="status" class="form-select bg-dark border-secondary border-opacity-20 text-white" required>
                                <option value="active" {{ $provider->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $provider->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted text-uppercase">API Key</label>
                            <input type="password" name="api_key" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                                   placeholder="Leave blank to keep current" required>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold">
                                    <i class="bi bi-check-lg me-2"></i> Update Provider
                                </button>
                                <a href="{{ route('admin.providers.index') }}" class="btn btn-secondary px-4 py-2 rounded-3">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
</style>
@endsection
