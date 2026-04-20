@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <img src="{{ asset('images/rms-logo.png') }}" alt="ProjectWise Logo" style="width: 60px; height: auto; margin: 0 auto 0.5rem; border-radius: 8px;">
        <div class="auth-logo">ProjectWise</div>
        <div class="auth-tagline">Research Management System</div>
    </div>

    <h2 style="font-size: 1.35rem; margin-bottom: 0.5rem; text-align: center; color: var(--ink);">Reset Password</h2>
    <p style="font-size: 0.9rem; color: var(--ink-mute); text-align: center; margin-bottom: 1.5rem;">Enter your email address and we'll send you instructions</p>

    @if(session('status'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.8125rem; color: #065f46; margin-bottom: 1rem;">
            {{ session('status') }}
        </div>
    @endif

    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.8125rem; color: #1e40af; margin-bottom: 1.5rem;">
        ℹ️ Password reset feature is under development. Please contact support for assistance.
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom: 1.5rem;">
            <label class="form-label" for="email">Email Address</label>
            <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn-primary" disabled style="opacity: 0.6; cursor: not-allowed;">Send Reset Link (Disabled)</button>
    </form>

    <p style="text-align: center; font-size: 0.875rem; color: var(--ink-mute); margin-top: 1.5rem;">
        Remember your password? <a href="{{ route('login') }}" class="auth-link">Sign in</a>
    </p>
</div>
@endsection