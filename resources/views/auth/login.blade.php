@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }

    .auth-root {
        width: 100vw;
        height: 100vh;
        display: flex;
        font-family: 'DM Sans', sans-serif;
        background: #e5e7eb;
        overflow: hidden;
    }

    /* ── LEFT PANEL ───────────────────────── */
    .auth-left {
        flex: 1.2;
        background: #d1d5db;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 2.5rem;
    }

    .left-top {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .left-logo {
        width: 200px;
        height: auto;
    }

    .brand-left {
        font-family: 'Syne', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
    }

    .left-bottom {
        display: flex;
        justify-content: center;
        align-items: flex-end;
    }

    .left-design {
        width: 100%;
        max-width: 500px;
        height: auto;
    }

    /* ── RIGHT PANEL ───────────────────────── */
    .auth-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem;
        background: #f8fafc;
        position: relative;
    }

    .right-brand {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        gap: .6rem;
        font-weight: 700;
        color: #111827;
        font-family: 'Syne', sans-serif;
    }

    .right-brand img {
        width: 28px;
        height: 28px;
    }

    .auth-card {
        width: 100%;
        max-width: 400px;
    }

    .auth-card h2 {
        font-size: 1.6rem;
        margin-bottom: .3rem;
        color: #111827;
    }

    .auth-card p {
        font-size: .9rem;
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        font-size: .85rem;
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: .4rem;
    }

    .form-group input {
        width: 100%;
        padding: .75rem .9rem;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        outline: none;
    }

    .form-group input:focus {
        border-color: #6b7280;
        box-shadow: 0 0 0 3px rgba(107,114,128,.15);
    }

    .btn {
        width: 100%;
        padding: .85rem;
        background: #374151;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn:hover {
        background: #111827;
    }

    .footer-text {
        margin-top: 1rem;
        text-align: center;
        font-size: .85rem;
        color: #6b7280;
    }

    .footer-text a {
        color: #374151;
        font-weight: 600;
        text-decoration: none;
    }

    .footer-text a:hover {
        text-decoration: underline;
    }

</style>

<div class="auth-root">

    {{-- LEFT --}}
    <div class="auth-left">

        <div class="left-top">
            <img src="{{ asset('images/rms-logo.png') }}" class="left-logo" alt="Logo">

            <div class="brand-left">
                ProjectWise – Research Management System
            </div>
        </div>

        <div class="left-bottom">
            <img src="{{ asset('images/rms-design.png') }}" class="left-design" alt="Design">
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="auth-right">

        {{-- brand top right --}}
        <div class="right-brand">
            <img src="{{ asset('images/rms-logo.png') }}" alt="logo">
            ProjectWise
        </div>

        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p>Sign in to continue to your dashboard</p>

            @if($errors->any())
                <p style="color:red;font-size:.85rem;margin-bottom:1rem;">
                    {{ $errors->first() }}
                </p>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button class="btn" type="submit">Sign In</button>
            </form>

            <div class="footer-text">
                Don't have an account?
                <a href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </div>

</div>
@endsection