@extends('layouts.master')

@section('title', 'User Management')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </nav>
                <h2 class="fw-bold h3 mb-1">User Management</h2>
                <p class="text-muted small">Manage system users, roles, and permissions across all tenants.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-bold">
                <i class="bi bi-person-plus me-2"></i> Add User
            </a>
        </div>

        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="bg-secondary bg-opacity-5">
                            <tr>
                                <th class="px-4 py-3 border-secondary border-opacity-10 small text-uppercase">User</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Role</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Tenant</th>
                                <th class="py-3 border-secondary border-opacity-10 small text-uppercase">Balance</th>
                                <th class="px-4 py-3 border-secondary border-opacity-10 text-end small text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff" class="rounded-circle" width="32">
                                            <div>
                                                <div class="fw-bold text-white">
                                                    {{ $user->name }}
                                                    @if($user->is_banned)
                                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-2" style="font-size:0.65rem;">BANNED</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill small">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td class="py-3 text-secondary">{{ $user->tenant->name ?? 'N/A' }}</td>
                                    <td class="py-3 fw-bold text-success">${{ number_format($user->wallet->balance ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 text-end">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-icon btn-sm text-info p-0" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-icon btn-sm text-secondary p-0 ms-2" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <button type="button" class="btn btn-icon btn-sm text-warning p-0 ms-2" title="{{ $user->is_banned ? 'Unban' : 'Ban' }}" onclick="toggleBan('{{ $user->id }}', {{ $user->is_banned ? 'false' : 'true' }})">
                                        <i class="bi bi-{{ $user->is_banned ? 'unlock' : 'slash-circle' }}"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-sm text-danger p-0 ms-2" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
</style>

@push('scripts')
<script>
    function toggleBan(userId, isCurrentlyBanned) {
        const action = isCurrentlyBanned ? 'unban' : 'ban';
        
        Swal.fire({
            title: `Are you sure you want to ${action} this user?`,
            text: `This action will ${action} the user's access to the system.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: isCurrentlyBanned ? '#198754' : '#dc3545',
            confirmButtonText: `Yes, ${action} user!`,
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${userId}/toggle-ban`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                });
            }
        });
    }
</script>
@endpush
@endsection
