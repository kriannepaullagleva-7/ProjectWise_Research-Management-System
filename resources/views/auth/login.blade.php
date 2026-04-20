@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<div class="auth-card" style="position: relative; overflow: hidden;">
    <!-- Decorative background -->
    <div style="position: absolute; top: -50%; right: -50%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(45, 91, 227, 0.1) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
    <div style="position: relative; z-index: 1;">
        <div class="auth-header">
            <img src="{{ asset('images/rms-logo.png') }}" alt="ProjectWise Logo" style="width: 60px; height: auto; margin: 0 auto 0.5rem; border-radius: 8px;">
            <div class="auth-logo">ProjectWise</div>
            <div class="auth-tagline">Research Management System</div>
        </div>

        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; text-align: center; color: #0f1117;">Welcome Back</h2>
        <p style="font-size: 0.9375rem; color: #8a8f9e; text-align: center; margin-bottom: 1.75rem;">Sign in to access your research dashboard</p>

        @if($errors->any())
            <div style="background: linear-gradient(135deg, #fef2f2 0%, #fde8e8 100%); border: 1px solid #fecaca; border-radius: 8px; padding: 1rem; font-size: 0.8125rem; color: #991b1b; margin-bottom: 1rem; display: flex; gap: 0.75rem; align-items: flex-start;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        @if(session('success'))
            <div style="background: linear-gradient(135deg, #ecfdf5 0%, #dffcf0 100%); border: 1px solid #a7f3d0; border-radius: 8px; padding: 1rem; font-size: 0.8125rem; color: #065f46; margin-bottom: 1rem; display: flex; gap: 0.75rem; align-items: flex-start;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;"><polyline points="20 6 9 17 4 12"/></svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label" for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0f1117;">Email Address</label>
                <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@university.edu" required autofocus style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e4e4e0; border-radius: 8px; font-size: 0.9375rem; transition: all 0.2s;">
                @error('email')<p class="form-error" style="color: #dc2626; margin-top: 0.5rem; font-size: 0.75rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 0.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label class="form-label" for="password" style="display: block; font-weight: 600; color: #0f1117; margin: 0;">Password</label>
                    <a href="{{ route('password.request') }}" style="font-size: 0.75rem; color: #2d5be3; text-decoration: none; font-weight: 500; transition: color 0.2s;">Forgot password?</a>
                </div>
                <input class="form-input" type="password" id="password" name="password" placeholder="••••••••" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e4e4e0; border-radius: 8px; font-size: 0.9375rem; transition: all 0.2s;">
                @error('password')<p class="form-error" style="color: #dc2626; margin-top: 0.5rem; font-size: 0.75rem;">{{ $message }}</p>@enderror
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.75rem;">
                <input type="checkbox" name="remember" id="remember" style="accent-color: #2d5be3; width: 16px; height: 16px; cursor: pointer; border-radius: 4px;">
                <label for="remember" style="font-size: 0.8125rem; color: #6b7280; cursor: pointer; user-select: none;">Remember me for 30 days</label>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.875rem; background: linear-gradient(135deg, #2d5be3 0%, #1e45c8 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.9375rem; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(45, 91, 227, 0.3);">Sign In</button>
        </form>

        <div style="display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0; opacity: 0.5;">
            <div style="flex: 1; height: 1px; background: #e4e4e0;"></div>
            <span style="font-size: 0.75rem; color: #8a8f9e; text-transform: uppercase; letter-spacing: 0.05em;">or</span>
            <div style="flex: 1; height: 1px; background: #e4e4e0;"></div>
        </div>

        <p style="text-align: center; font-size: 0.875rem; color: #8a8f9e;">
            Don't have an account? <a href="{{ route('register') }}" style="color: #2d5be3; text-decoration: none; font-weight: 600; transition: color 0.2s;">Create one</a>
        </p>
    </div>
</div>

<style>
    .form-input:hover {
        border-color: #d1d5db;
    }
    .form-input:focus {
        outline: none;
        border-color: #2d5be3;
        box-shadow: 0 0 0 3px rgba(45, 91, 227, 0.1);
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(45, 91, 227, 0.4) !important;
    }
    .btn-primary:active {
        transform: translateY(0);
    }
</style>
@endsection