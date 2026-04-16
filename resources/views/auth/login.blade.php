@extends('layouts.auth')
@section('title', 'Sign In')
 
@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo">ProjectWise</div>
        <div class="auth-tagline">Research Management System</div>
    </div>
 
    <h2 style="font-size:1.35rem;margin-bottom:1.5rem;text-align:center;">Welcome back</h2>
 
    @if($errors->any())
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:.75rem 1rem;font-size:.8125rem;color:#991b1b;margin-bottom:1rem;">
        {{ $errors->first() }}
    </div>
    @endif
 
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div style="margin-bottom:1.125rem;">
            <label class="form-label" for="email">Email address</label>
            <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@university.edu" required autofocus>
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div style="margin-bottom:1.5rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem;">
                <label class="form-label" for="password" style="margin-bottom:0;">Password</label>
                <a href="{{ route('password.request') }}" class="auth-link" style="font-size:.75rem;">Forgot password?</a>
            </div>
            <input class="form-input" type="password" id="password" name="password" placeholder="••••••••" required>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;">
            <input type="checkbox" name="remember" id="remember" style="accent-color:var(--accent);width:15px;height:15px;">
            <label for="remember" style="font-size:.8125rem;color:#6b7280;cursor:pointer;">Remember me for 30 days</label>
        </div>
        <button type="submit" class="btn-primary">Sign in</button>
    </form>
 
    <div class="divider">or</div>
    <p style="text-align:center;font-size:.875rem;color:#6b7280;">
        Don't have an account? <a href="{{ route('register') }}" class="auth-link">Create one</a>
    </p>
</div>
@endsection