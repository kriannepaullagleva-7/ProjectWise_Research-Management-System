@extends('layouts.app')
@section('title', 'Profile & Settings')
@section('page-title', 'Profile & Settings')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap');

    .pf-root {
        font-family: 'DM Sans', sans-serif;
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.5rem;
        max-width: 1100px;
    }

    @media (max-width: 800px) { .pf-root { grid-template-columns: 1fr; } }

    /* Cards */
    .pf-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .pf-card-head {
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pf-card-head h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
    }

    .pf-card-body { padding: 1.75rem; }

    /* Avatar */
    .pf-avatar-row {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #f1f5f9;
        background: #fafafa;
    }

    .pf-avatar-wrap {
        position: relative;
        width: 72px;
        height: 72px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .pf-avatar-wrap img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e5e7eb;
    }

    .pf-avatar-wrap input { display: none; }

    .pf-avatar-overlay {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: rgba(0,0,0,.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity .15s;
        color: #fff;
        font-size: .7rem;
        font-weight: 600;
    }

    .pf-avatar-wrap:hover .pf-avatar-overlay { opacity: 1; }

    .pf-avatar-info h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: .2rem;
    }

    .pf-avatar-info p { font-size: .8125rem; color: #64748b; }

    /* Form grid */
    .pf-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.1rem;
    }

    @media (max-width: 600px) { .pf-form-grid { grid-template-columns: 1fr; } }

    .pf-field { display: flex; flex-direction: column; gap: .4rem; }
    .pf-field.full { grid-column: span 2; }

    .pf-label {
        font-size: .8rem;
        font-weight: 600;
        color: #374151;
        letter-spacing: .01em;
    }

    .pf-input {
        padding: .7rem .95rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        color: #0f172a;
        background: #fafafa;
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }

    .pf-input:focus {
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }

    .pf-error { font-size: .75rem; color: #dc2626; }

    /* Buttons */
    .pf-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: #fff;
        padding: .6rem 1.25rem;
        border: none;
        border-radius: 9px;
        font-size: .85rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
    }

    .pf-btn-primary:hover { opacity: .9; transform: translateY(-1px); }

    .pf-btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #f1f5f9;
        color: #374151;
        padding: .6rem 1.25rem;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        font-size: .85rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
        transition: background .1s;
    }

    .pf-btn-ghost:hover { background: #e2e8f0; }

    /* Danger zone */
    .pf-danger-section {
        margin-top: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 10px;
        border: 1.5px solid #fecaca;
        background: #fef2f2;
    }

    .pf-danger-section h4 { font-size: .85rem; font-weight: 700; color: #7f1d1d; margin-bottom: .25rem; }
    .pf-danger-section p { font-size: .78rem; color: #991b1b; margin-bottom: .75rem; }

    .pf-success-alert {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 10px;
        padding: .7rem 1rem;
        font-size: .8125rem;
        color: #065f46;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
</style>

<div class="pf-root">

    {{-- PERSONAL INFORMATION --}}
    <div class="pf-card">

        <div class="pf-avatar-row">
            <label class="pf-avatar-wrap">
                <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="Avatar">
                <input type="file" name="avatar_preview" id="avatarInput" accept="image/*">
                <div class="pf-avatar-overlay">📷 Edit</div>
            </label>
            <div class="pf-avatar-info">
                <h4>{{ $user->full_name }}</h4>
                <p>{{ ucfirst($user->role) }} · {{ $user->department ?? 'No department set' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="pf-card-head">
                <h3>Personal Information</h3>
                <button type="submit" class="pf-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Changes
                </button>
            </div>

            <div class="pf-card-body">

                @if(session('profile_updated'))
                    <div class="pf-success-alert">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Profile updated successfully.
                    </div>
                @endif

                {{-- Hidden file input that travels with the form --}}
                <input type="file" name="avatar" id="avatarFormInput" accept="image/*" style="display:none;">

                <div class="pf-form-grid">
                    <div class="pf-field">
                        <label class="pf-label">First name</label>
                        <input class="pf-input" type="text" name="first_name"
                               value="{{ old('first_name', $user->first_name) }}" required>
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">Last name</label>
                        <input class="pf-input" type="text" name="last_name"
                               value="{{ old('last_name', $user->last_name) }}" required>
                    </div>

                    <div class="pf-field full">
                        <label class="pf-label">Email address</label>
                        <input class="pf-input" type="email" name="email"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="pf-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">Department</label>
                        <input class="pf-input" type="text" name="department"
                               value="{{ old('department', $user->department) }}"
                               placeholder="e.g. Computer Science">
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">Student / Faculty ID</label>
                        <input class="pf-input" type="text" name="student_id"
                               value="{{ old('student_id', $user->student_id) }}"
                               placeholder="e.g. 2024-00123">
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- CHANGE PASSWORD --}}
        <div class="pf-card">
            <div class="pf-card-head">
                <h3>Change Password</h3>
            </div>

            <div class="pf-card-body">

                @if(session('password_updated'))
                    <div class="pf-success-alert">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Password changed successfully.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.password') }}"
                      style="display:flex;flex-direction:column;gap:1rem;">
                    @csrf
                    @method('PUT')

                    <div class="pf-field">
                        <label class="pf-label">Current password</label>
                        <input class="pf-input" type="password" name="current_password" required>
                        @error('current_password')
                            <p class="pf-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">New password</label>
                        <input class="pf-input" type="password" name="password" required>
                        @error('password')
                            <p class="pf-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">Confirm new password</label>
                        <input class="pf-input" type="password" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="pf-btn-primary" style="width:100%;justify-content:center;">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

        {{-- ACCOUNT INFO --}}
        <div class="pf-card">
            <div class="pf-card-head">
                <h3>Account Details</h3>
            </div>
            <div class="pf-card-body" style="font-size:.8125rem;display:flex;flex-direction:column;gap:.65rem;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#64748b;">Role</span>
                    <span style="font-weight:600;color:#0f172a;text-transform:capitalize;">{{ $user->role }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#64748b;">Member since</span>
                    <span style="font-weight:600;color:#0f172a;">{{ $user->created_at->format('M Y') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#64748b;">Status</span>
                    <span style="background:#d1fae5;color:#065f46;padding:.15rem .6rem;border-radius:99px;font-size:.72rem;font-weight:700;">Active</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// document.getElementById('avatarInput').addEventListener('change', function(e) {
//     if (!e.target.files.length) return;
//     const reader = new FileReader();
//     reader.onload = function() {
//         document.getElementById('avatarPreview').src = reader.result;
//         // Transfer to the form's hidden file input
//         const dt = new DataTransfer();
//         dt.items.add(e.target.files[0]);
//         document.getElementById('avatarFormInput').files = dt.files;
//     };
//     reader.readAsDataURL(e.target.files[0]);
// });
</script>
@endsection