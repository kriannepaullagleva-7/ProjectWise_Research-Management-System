@extends('layouts.app')
@section('title', 'Profile & Settings')
@section('page-title', 'Profile & Settings')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;max-width:900px;">
    {{-- Profile Info --}}
    <div class="card">
        <div class="card-header"><h3>Personal Information</h3></div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
                <img src="{{ $user->avatar_url }}" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid var(--border);" alt="">
                <div>
                    <div style="font-weight:600;font-size:1.0625rem;">{{ $user->full_name }}</div>
                    <div style="font-size:.8125rem;color:var(--ink-mute);text-transform:capitalize;">{{ $user->role }} · {{ $user->department ?? 'No department' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label class="form-label">First name</label>
                        <input class="form-input" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    </div>
                    <div>
                        <label class="form-label">Last name</label>
                        <input class="form-input" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Email</label>
                    <input class="form-input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label class="form-label">Department</label>
                        <input class="form-input" type="text" name="department" value="{{ old('department', $user->department) }}">
                    </div>
                    <div>
                        <label class="form-label">Student / Faculty ID</label>
                        <input class="form-input" type="text" name="student_id" value="{{ old('student_id', $user->student_id) }}">
                    </div>
                </div>
                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Profile Picture</label>
                    <input class="form-input" type="file" name="avatar" accept="image/*" style="padding:.4rem .875rem;">
                    <p style="font-size:.72rem;color:var(--ink-mute);margin-top:.3rem;">JPG, PNG, GIF — max 2MB</p>
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Password --}}
    <div class="card">
        <div class="card-header"><h3>Change Password</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf @method('PUT')
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Current password</label>
                    <input class="form-input" type="password" name="current_password" required>
                    @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="form-label">New password</label>
                    <input class="form-input" type="password" name="password" required>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label class="form-label">Confirm new password</label>
                    <input class="form-input" type="password" name="password_confirmation" required>
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>

        {{-- Account Info --}}
        <div style="padding:1.25rem 1.5rem;border-top:1px solid var(--border);">
            <h3 style="font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:600;margin-bottom:.875rem;">Account Details</h3>
            @foreach([
                ['Email verified', $user->email_verified_at ? '✓ Verified on ' . $user->email_verified_at->format('M d, Y') : '✗ Not verified'],
                ['Member since', $user->created_at->format('F d, Y')],
                ['Role', ucfirst($user->role)],
                ['Total submissions', $user->researchProjects()->count()],
            ] as [$label, $val])
            <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid #f9fafb;font-size:.8125rem;">
                <span style="color:var(--ink-mute);">{{ $label }}</span>
                <span style="font-weight:500;color:var(--ink-soft);">{{ $val }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection