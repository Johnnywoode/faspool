@extends('layouts.master')

@section('title', 'Referral Program')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-10">
        <div class="mb-4">
            <h2 class="fw-bold h3 mb-1">Referral <span class="text-primary">Program</span></h2>
            <p class="text-muted">Earn wallet credits by inviting friends to Faspool.</p>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-people-fill fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Referrals</div>
                            <div class="h4 fw-bold text-white mb-0">0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-cash-stack fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Earnings</div>
                            <div class="h4 fw-bold text-white mb-0">$0.00</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-surface border-secondary border-opacity-10 rounded-4 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-gift fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Bonus Credits</div>
                            <div class="h4 fw-bold text-white mb-0">$0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Link -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Your Referral Link</h6>
                <div class="input-group">
                    <input type="text" class="form-control bg-dark border-secondary border-opacity-20 text-white" value="{{ url('/register?ref=' . auth()->id()) }}" readonly>
                    <button class="btn btn-primary px-4" onclick="navigator.clipboard.writeText('{{ url('/register?ref=' . auth()->id()) }}')">
                        <i class="bi bi-clipboard me-2"></i> Copy
                    </button>
                </div>
                <div class="text-muted small mt-2">Share this link with friends. You earn 5% of their first deposit!</div>
            </div>
        </div>

        <!-- Referral History -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Referral History</h6>
            </div>
            <div class="card-body p-0">
                <div class="text-center py-5">
                    <i class="bi bi-people fs-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted">No referrals yet. Start sharing your link!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
</style>
@endsection
