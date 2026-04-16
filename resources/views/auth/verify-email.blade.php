@extends('layouts.auth')
@section('title', 'Verify Email')
 
@section('content')
<div class="auth-card" style="text-align:center;">
    <div style="width:64px;height:64px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2d5be3" stroke-width="1.75">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </svg>
    </div>
    <h2 style="font-size:1.35rem;margin-bottom:.75rem;">Check your email</h2>
    <p style="color:#6b7280;font-size:.9rem;line-height:1.6;margin-bottom:1.75rem;">
        We sent a verification link to <strong>{{ auth()->user()->email }}</strong>. Click the link to activate your account and access ProjectWise.
    </p>
 
    @if(session('status') === 'verification-link-sent')
    <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:8px;padding:.75rem 1rem;font-size:.8125rem;color:#065f46;margin-bottom:1rem;">
        A new verification link has been sent to your email address.
    </div>
    @endif
 
    <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:1rem;">
        @csrf
        <button type="submit" class="btn-primary">Resend verification email</button>
    </form>
 
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;color:#6b7280;font-size:.875rem;font-family:inherit;text-decoration:underline;">Sign out</button>
    </form>
</div>
@endsection