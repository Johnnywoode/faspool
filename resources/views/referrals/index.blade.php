@extends('layouts.master')

@section('title', 'Referral Program')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-10">
        <div class="mb-4">
            <h2 class="fw-bold h3 mb-1">Referral <span class="text-primary">Program</span></h2>
            <p class="text-muted">Invite your friends and earn commissions on their verification orders.</p>
        </div>

        <div class="row g-4 mb-4">
            <!-- Referral Stats -->
            <div class="col-md-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-circle d-inline-flex mb-3">
                            <i class="ph ph-users text-primary fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">{{ $referrals->total() }}</h3>
                        <p class="text-muted small mb-0">Total Referrals</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="p-3 bg-success bg-opacity-10 rounded-circle d-inline-flex mb-3">
                            <i class="ph ph-currency-dollar text-success fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">GHS {{ number_format($totalEarnings, 2) }}</h3>
                        <p class="text-muted small mb-0">Total Earned</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-circle d-inline-flex mb-3">
                            <i class="ph ph-percent text-warning fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">5%</h3>
                        <p class="text-muted small mb-0">Commission Rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Link Card -->
        <div class="card bg-primary bg-opacity-10 border border-primary border-opacity-20 rounded-5 p-4 p-md-5 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h3 class="fw-bold text-white mb-2">Share the love</h3>
                    <p class="text-secondary mb-0">Copy your unique referral link below and share it with your audience or friends. You earn whenever they spend.</p>
                </div>
                <div class="col-lg-5">
                    <div class="input-group">
                        <input type="text" class="form-control bg-dark border-secondary border-opacity-20 text-white py-3 px-4" value="{{ $referralLink }}" id="referralLink" readonly>
                        <button class="btn btn-primary px-4 fw-bold" onclick="copyReferralLink()">
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referrals Table -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i> Recently Referred Users</h6>
            </div>
            <div class="card-body p-0">
                @if($referrals->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-person-plus fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">You haven't referred anyone yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-secondary bg-opacity-5">
                                <tr>
                                    <th class="px-4 py-3 small text-uppercase">User</th>
                                    <th class="py-3 small text-uppercase">Joined</th>
                                    <th class="py-3 small text-uppercase">Status</th>
                                    <th class="px-4 py-3 text-end small text-uppercase">Contribution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($referrals as $ref)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="ph ph-user"></i>
                                                </div>
                                                <div class="fw-bold text-white small">{{ $ref->name }}</div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-muted small">
                                            {{ $ref->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded small">ACTIVE</span>
                                        </td>
                                        <td class="px-4 py-3 text-end text-success fw-bold small">
                                            +GHS 0.00
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top border-secondary border-opacity-10">
                        {{ $referrals->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
</style>

<script>
    function copyReferralLink() {
        const copyText = document.getElementById("referralLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        // Optional: Show a toast or feedback
        alert("Referral link copied!");
    }
</script>
@endsection
