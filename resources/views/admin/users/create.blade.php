@extends('layouts.master')

@section('title', 'Create User')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="mb-4">
            <h2 class="fw-bold h3 mb-1">Create <span class="text-primary">User</span></h2>
            <p class="text-muted small">Add a new user to the system.</p>
        </div>

        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Name</label>
                            <input type="text" name="name" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                                   placeholder="John Doe" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Email</label>
                            <input type="email" name="email" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                                   placeholder="john@example.com" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Password</label>
                            <input type="password" name="password" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                                   placeholder="Min 8 characters" required>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary border-opacity-20 text-white" 
                                   placeholder="Confirm password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Tenant</label>
                            <select name="tenant_id" class="form-select bg-dark border-secondary border-opacity-20 text-white" required>
                                <option value="" disabled selected>Select tenant...</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Role</label>
                            <select name="role" class="form-select bg-dark border-secondary border-opacity-20 text-white" required>
                                <option value="" disabled selected>Select role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold">
                                    <i class="bi bi-check-lg me-2"></i> Create User
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4 py-2 rounded-3">
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
