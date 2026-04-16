@extends('layouts.auth')
@section('title', 'Create Account')
 
@section('content')
<div class="auth-card" style="max-width:520px;">
    <div class="auth-header">
        <div class="auth-logo">ProjectWise</div>
        <div class="auth-tagline">Research Management System</div>
    </div>
 
    <h2 style="font-size:1.35rem;margin-bottom:1.5rem;text-align:center;">Create your account</h2>
 
    @if($errors->any())
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:.75rem 1rem;font-size:.8125rem;color:#991b1b;margin-bottom:1rem;">
        {{ $errors->first() }}
    </div>
    @endif
 
    <form method="POST" action="{{ route('register') }}">
        @csrf
 
        <div class="row-2" style="margin-bottom:1rem;">
            <div>
                <label class="form-label" for="first_name">First name</label>
                <input class="form-input" type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                @error('first_name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="last_name">Last name</label>
                <input class="form-input" type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                @error('last_name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
 
        <div style="margin-bottom:1rem;">
            <label class="form-label" for="email">Email address</label>
            <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
 
        <div style="margin-bottom:1rem;">
            <label class="form-label" for="role">I am a</label>
            <select class="form-input" id="role" name="role" required>
                <option value="">Select role…</option>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                <option value="faculty" {{ old('role') === 'faculty' ? 'selected' : '' }}>Faculty / Teacher</option>
            </select>
            @error('role')<p class="form-error">{{ $message }}</p>@enderror
        </div>
 
        <div class="row-2" style="margin-bottom:1rem;">
            <div>
                <label class="form-label" for="department">Department</label>
                <input class="form-input" type="text" id="department" name="department" value="{{ old('department') }}" placeholder="e.g. Computer Science">
            </div>
            <div>
                <label class="form-label" for="student_id">Student / Faculty ID <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                <input class="form-input" type="text" id="student_id" name="student_id" value="{{ old('student_id') }}">
            </div>
        </div>
 
        <div class="row-2" style="margin-bottom:1.5rem;">
            <div>
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password" required>
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="password_confirmation">Confirm password</label>
                <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
        </div>
 
        <button type="submit" class="btn-primary">Create account</button>
    </form>
 
    <p style="text-align:center;font-size:.875rem;color:#6b7280;margin-top:1.5rem;">
        Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in</a>
    </p>
</div>
@endsection