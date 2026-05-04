@extends('layouts.master')

@section('title', 'Tenant: ' . $tenant->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}" class="text-decoration-none">Tenants</a></li>
                <li class="breadcrumb-item active">{{ $tenant->name }}</li>
            </ol>
        </nav>
        <h1 class="h4 fw-bold mb-0">{{ $tenant->name }}</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.tenants.toggle-status', $tenant) }}">
            @csrf
            <button class="btn btn-sm {{ $tenant->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                <i class="bi bi-{{ $tenant->status === 'active' ? 'pause' : 'play' }} me-1"></i>
                {{ $tenant->status === 'active' ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
        @unless($tenant->is_default)
        <form method="POST" action="{{ route('admin.tenants.make-default', $tenant) }}">
            @csrf
            <button class="btn btn-sm btn-outline-primary">
                <i class="bi bi-star me-1"></i> Make Default
            </button>
        </form>
        @endunless
    </div>
</div>

<div class="row g-4">
    {{-- Info Card --}}
    <div class="col-lg-5">
        <div class="card border-0 h-100" style="background:var(--bs-secondary-bg)">
            <div class="card-header border-0 bg-transparent fw-semibold">Tenant Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-secondary">Domain</dt>
                    <dd class="col-7">{{ $tenant->domain ?? '—' }}</dd>

                    <dt class="col-5 text-secondary">Status</dt>
                    <dd class="col-7">
                        @php
                            $badge = match($tenant->status) {
                                'active'    => 'success',
                                'inactive'  => 'secondary',
                                'suspended' => 'danger',
                                default     => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($tenant->status) }}</span>
                    </dd>

                    <dt class="col-5 text-secondary">Default</dt>
                    <dd class="col-7">
                        @if($tenant->is_default)
                            <span class="badge bg-primary">Yes</span>
                        @else
                            <span class="text-secondary">No</span>
                        @endif
                    </dd>

                    <dt class="col-5 text-secondary">Users</dt>
                    <dd class="col-7">{{ number_format($tenant->users_count) }}</dd>

                    <dt class="col-5 text-secondary">Orders</dt>
                    <dd class="col-7">{{ number_format($tenant->orders_count) }}</dd>

                    <dt class="col-5 text-secondary">API Key</dt>
                    <dd class="col-7">
                        <code class="small text-break">{{ $tenant->api_key }}</code>
                    </dd>

                    <dt class="col-5 text-secondary">Created</dt>
                    <dd class="col-7">{{ $tenant->created_at->format('M d, Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="col-lg-7">
        <div class="card border-0" style="background:var(--bs-secondary-bg)">
            <div class="card-header border-0 bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                Users
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead>
                            <tr class="text-secondary small">
                                <th class="ps-4">Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenant->users()->with('roles')->latest()->take(10)->get() as $user)
                            <tr>
                                <td class="ps-4">{{ $user->name }}</td>
                                <td class="text-secondary small">{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary-subtle text-primary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-secondary small">{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">No users yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
