@extends('layouts.app')
@section('title', 'Create New User')
@section('page-title', 'Create New User')

@section('content')
<div style="max-width: 600px;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.users') }}" style="color: #2563eb; text-decoration: none; font-size: 0.875rem;">← Back to Users</a>
    </div>

    <div style="background: white; border-radius: 0.5rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h1 style="margin: 0 0 1.5rem 0; font-size: 1.5rem; font-weight: 700;">Create New User</h1>

        @if ($errors->any())
        <div style="background: #fee2e2; color: #7f1d1d; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">
                    Full Name <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('name')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">
                    Email <span style="color: #dc2626;">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('email')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="role" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">
                    Role <span style="color: #dc2626;">*</span>
                </label>
                <select name="role" id="role" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">-- Select Role --</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="faculty" {{ old('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="department" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">
                    Department
                </label>
                <input type="text" name="department" id="department" value="{{ old('department') }}" placeholder="e.g. Computer Science" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('department')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">
                        Password <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="password" name="password" id="password" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    @error('password')<p style="color: #dc2626; margin-top: 0.25rem; font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">
                        Confirm Password <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>
            </div>

            <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <button type="submit" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">Create User</button>
                <a href="{{ route('admin.users') }}" style="background: #e5e7eb; color: #374151; padding: 0.75rem 1.5rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
            </form>
        </div>
    </div>
</div>
@endsection