@extends('layouts.master')

@section('title', __('menu.tenants'))

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold h3 mb-1">{{ __('admin.tenants.title') }}</h2>
                <p class="text-muted small">{{ __('admin.tenants.subtitle') }}</p>
            </div>
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-bold">
                <i class="bi bi-plus-lg me-2"></i> {{ __('admin.tenants.create') }}
            </a>

        </div>

        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="bg-secondary bg-opacity-5">
                            <tr>
                                <th class="px-4 py-3 border-secondary border-opacity-10 small text-uppercase">{{ __('admin.tenants.table.name') }}</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">{{ __('admin.tenants.table.domain') }}</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">{{ __('admin.tenants.table.status') }}</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">{{ __('admin.tenants.table.users') }}</th>
                                <th class="px-4 py-3 border-secondary border-opacity-10 text-end small text-uppercase">{{ __('admin.tenants.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenants ?? \App\Models\Tenant::all() as $tenant)
                                <tr>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="text-decoration-none d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="bi bi-building"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-white">{{ $tenant->name }}</div>
                                                <div class="text-muted small">{{ $tenant->uid }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="py-3 text-secondary">{{ $tenant->domain ?? 'default.faspool.com' }}</td>
                                    <td class="py-3">
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">{{ __('admin.tenants.table.active') }}</span>
                                    </td>
                                    <td class="py-3 text-secondary">{{ $tenant->users_count ?? 0 }}</td>
                                    <td class="px-4 py-3 text-end">

                                        <button class="btn btn-icon btn-sm text-secondary p-0"><i class="bi bi-pencil-square"></i></button>
                                        <button class="btn btn-icon btn-sm text-danger p-0 ms-2"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">{{ __('admin.tenants.table.no_data') }}</td>
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
