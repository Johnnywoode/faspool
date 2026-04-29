@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <h2 class="fw-bold">FAS<span class="text-primary">POOL</span></h2>
        <p class="text-muted">Create your enterprise account</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        @if(request()->has('ref'))
            <input type="hidden" name="ref" value="{{ request()->get('ref') }}">
        @endif
        <div class="mb-3">
            <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label text-muted small fw-bold text-uppercase">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label text-muted small fw-bold text-uppercase">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="premium-btn mb-3">Create Account</button>

        <div class="text-center">
            <p class="text-muted small">Already have an account? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">Login</a></p>
        </div>
    </form>
</div>
@endsection
