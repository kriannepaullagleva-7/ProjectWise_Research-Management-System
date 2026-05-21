@extends('layouts.app')
@section('title', 'Create New User')
@section('page-title', 'Create New User')

@section('content')
<div style="max-width:620px;">

    <div style="margin-bottom:1.25rem;">
        <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Users
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>New User Account</h3>
        </div>
        <div class="card-body">

            @if($errors->any())
            <div class="flash flash-error" style="margin-bottom:1.25rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}" style="display:flex;flex-direction:column;gap:1.125rem;">
                @csrf

                <div>
                    <label class="form-label" for="name">
                        Full Name <span style="color:var(--danger);">*</span>
                    </label>
                    <input class="form-input" type="text" id="name" name="name"
                           value="{{ old('name') }}" required placeholder="e.g. Juan dela Cruz">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="email">
                        Email Address <span style="color:var(--danger);">*</span>
                    </label>
                    <input class="form-input" type="email" id="email" name="email"
                           value="{{ old('email') }}" required placeholder="user@university.edu">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label class="form-label" for="role">
                            Role <span style="color:var(--danger);">*</span>
                        </label>
                        <select class="form-input" id="role" name="role" required>
                            <option value="">— Select role —</option>
                            <option value="student"  {{ old('role') === 'student'  ? 'selected' : '' }}>Student</option>
                            <option value="faculty"  {{ old('role') === 'faculty'  ? 'selected' : '' }}>Faculty</option>
                            <option value="admin"    {{ old('role') === 'admin'    ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="department">Department</label>
                        <input class="form-input" type="text" id="department" name="department"
                               value="{{ old('department') }}" placeholder="e.g. Computer Science">
                        @error('department')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label class="form-label" for="password">
                            Password <span style="color:var(--danger);">*</span>
                        </label>
                        <input class="form-input" type="password" id="password" name="password"
                               required placeholder="Min. 8 characters">
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label" for="password_confirmation">
                            Confirm Password <span style="color:var(--danger);">*</span>
                        </label>
                        <input class="form-input" type="password" id="password_confirmation"
                               name="password_confirmation" required placeholder="Repeat password">
                    </div>
                </div>

                <div style="display:flex;gap:.75rem;padding-top:1rem;border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Create User
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
