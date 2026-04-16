@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; font-size: 1.875rem; font-weight: 700;">My Profile</h1>

    <div style="background: white; border-radius: 0.5rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <div style="margin-bottom: 1rem;">
                <label style="color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Name</label>
                <p style="margin: 0.5rem 0 0 0; font-size: 1.125rem; font-weight: 600;">{{ $user->name }}</p>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Full Name</label>
                <p style="margin: 0.5rem 0 0 0; font-size: 1.125rem;">{{ $user->full_name ?? 'Not set' }}</p>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Email</label>
                <p style="margin: 0.5rem 0 0 0; font-size: 1rem;">{{ $user->email }}</p>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Role</label>
                <p style="margin: 0.5rem 0 0 0; font-size: 1rem; text-transform: capitalize;">{{ $user->role }}</p>
            </div>
            <div>
                <label style="color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Department</label>
                <p style="margin: 0.5rem 0 0 0; font-size: 1rem;">{{ $user->department ?? 'Not set' }}</p>
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('profile.edit') }}" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600;">Edit Profile</a>
        </div>
    </div>
</div>
@endsection
