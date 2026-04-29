@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;min-height:100vh;">

    {{-- LEFT — Branding --}}
    <div style="background:linear-gradient(145deg,#1a3da5 0%,#2d5be3 55%,#4f7bff 100%);display:flex;flex-direction:column;justify-content:space-between;padding:3rem 2.5rem;position:relative;overflow:hidden;color:#fff;">
        <div style="position:absolute;top:-90px;right:-90px;width:320px;height:320px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-70px;left:-70px;width:260px;height:260px;background:rgba(255,255,255,.05);border-radius:50%;"></div>

        <div style="position:relative;z-index:1;display:flex;align-items:center;gap:1rem;">
            <div style="width:56px;height:56px;border-radius:12px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            <div>
                <div style="font-family:'DM Serif Display',serif;font-size:1.75rem;line-height:1;letter-spacing:-.01em;">ProjectWise</div>
                <div style="font-size:.8rem;opacity:.75;margin-top:2px;letter-spacing:.06em;text-transform:uppercase;">Research Management System</div>
            </div>
        </div>

        <div style="position:relative;z-index:1;">
            <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:16px;padding:2rem;backdrop-filter:blur(8px);">
                <h3 style="font-family:'DM Serif Display',serif;font-size:1.35rem;margin-bottom:1.25rem;">Collaborate &amp; Discover</h3>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.875rem;">
                    @foreach(['Share your research with the academic community','Get expert faculty feedback &amp; peer reviews','Build your academic portfolio &amp; network'] as $feat)
                    <li style="display:flex;align-items:center;gap:.75rem;font-size:.9rem;opacity:.92;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
                        {!! $feat !!}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div style="position:relative;z-index:1;font-size:.8rem;opacity:.6;">
            &copy; {{ date('Y') }} ProjectWise. University of Mindanao.
        </div>
    </div>

    {{-- RIGHT — Form --}}
    <div style="display:flex;flex-direction:column;justify-content:center;padding:3rem 2.5rem;background:#fff;">
        <div style="max-width:420px;width:100%;margin:0 auto;">

            <div style="margin-bottom:2rem;">
                <h2 style="font-family:'DM Serif Display',serif;font-size:1.85rem;color:#0f1117;margin-bottom:.375rem;">Welcome back</h2>
                <p style="color:#8a8f9e;font-size:.9375rem;margin:0;">Sign in to your research dashboard</p>
            </div>

            @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:.875rem 1rem;font-size:.8125rem;color:#991b1b;margin-bottom:1.5rem;display:flex;gap:.65rem;align-items:flex-start;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('success'))
            <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;padding:.875rem 1rem;font-size:.8125rem;color:#065f46;margin-bottom:1.5rem;display:flex;gap:.65rem;align-items:flex-start;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
                @csrf

                <div>
                    <label for="email" style="display:block;font-size:.8125rem;font-weight:600;color:#374151;margin-bottom:.4rem;">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                           placeholder="you@university.edu"
                           style="width:100%;padding:.75rem 1rem;border:1.5px solid #e4e4e0;border-radius:10px;font-size:.9375rem;font-family:inherit;color:#0f1117;background:#fafafa;outline:none;transition:border-color .15s,box-shadow .15s;"
                           onfocus="this.style.borderColor='#2d5be3';this.style.boxShadow='0 0 0 3px #eef1fd'"
                           onblur="this.style.borderColor='#e4e4e0';this.style.boxShadow='none'">
                </div>

                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem;">
                        <label for="password" style="font-size:.8125rem;font-weight:600;color:#374151;">Password</label>
                        <a href="{{ route('password.request') }}" style="font-size:.8rem;color:#2d5be3;text-decoration:none;font-weight:500;">Forgot password?</a>
                    </div>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           style="width:100%;padding:.75rem 1rem;border:1.5px solid #e4e4e0;border-radius:10px;font-size:.9375rem;font-family:inherit;color:#0f1117;background:#fafafa;outline:none;transition:border-color .15s,box-shadow .15s;"
                           onfocus="this.style.borderColor='#2d5be3';this.style.boxShadow='0 0 0 3px #eef1fd'"
                           onblur="this.style.borderColor='#e4e4e0';this.style.boxShadow='none'">
                </div>

                <div style="display:flex;align-items:center;gap:.5rem;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:#2d5be3;width:15px;height:15px;cursor:pointer;">
                    <label for="remember" style="font-size:.8125rem;color:#6b7280;cursor:pointer;">Remember me for 30 days</label>
                </div>

                <button type="submit"
                        style="padding:.825rem;background:linear-gradient(135deg,#2d5be3,#1e45c8);color:#fff;border:none;border-radius:10px;font-weight:600;font-size:.9375rem;font-family:inherit;cursor:pointer;transition:transform .15s,box-shadow .15s;"
                        onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(45,91,227,.4)'"
                        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                    Sign In →
                </button>
            </form>

            <div style="display:flex;align-items:center;gap:1rem;margin:1.75rem 0;opacity:.4;">
                <div style="flex:1;height:1px;background:#e4e4e0;"></div>
                <span style="font-size:.75rem;color:#8a8f9e;">or</span>
                <div style="flex:1;height:1px;background:#e4e4e0;"></div>
            </div>

            <p style="text-align:center;font-size:.875rem;color:#8a8f9e;margin:0;">
                Don't have an account?
                <a href="{{ route('register') }}" style="color:#2d5be3;text-decoration:none;font-weight:600;margin-left:.2rem;">Create one</a>
            </p>

        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns:1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
    div[style*="background:linear-gradient(145deg"] {
        display: none !important;
    }
}
</style>
@endsection
