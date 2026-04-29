@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <h2 class="fw-bold">FAS<span class="text-primary">POOL</span></h2>
        <p class="text-muted">Sign in to your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label text-muted small fw-bold text-uppercase">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="premium-btn mb-3">Sign In</button>

        <div class="text-center">
            <p class="text-muted small">Don't have an account? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold">Register</a></p>
        </div>
    </form>
</div>
@endsection
