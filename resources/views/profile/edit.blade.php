@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; font-size: 1.875rem; font-weight: 700;">Edit Profile</h1>

    <div style="background: white; border-radius: 0.5rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}" disabled style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #f3f4f6; color: #6b7280;">
                <p style="color: #6b7280; margin-top: 0.25rem; font-size: 0.875rem;">Email cannot be changed</p>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Display Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('name')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="full_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $user->full_name) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('full_name')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="department" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Department</label>
                    <input type="text" name="department" id="department" value="{{ old('department', $user->department) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    @error('department')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="student_id" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Student/Faculty ID</label>
                    <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $user->student_id ?? '') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    @error('student_id')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="bio" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Bio</label>
                <textarea name="bio" id="bio" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; resize: vertical;" placeholder="Tell others about yourself...">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">Save Changes</button>
                <a href="{{ route('profile.show') }}" style="background: #e5e7eb; color: #374151; padding: 0.75rem 1.5rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600;">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Change Password Section -->
    <div style="background: white; border-radius: 0.5rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 600;">Change Password</h2>

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label for="current_password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Current Password</label>
                <input type="password" name="current_password" id="current_password" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('current_password')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">New Password</label>
                <input type="password" name="password" id="password" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('password')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password_confirmation" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>

            <button type="submit" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">Update Password</button>
        </form>
    </div>
</div>
@endsection
