@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem;">
    <div>
        <form method="GET" style="display:flex;gap:.65rem;">
            <input class="form-input" style="width:230px;" type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email…">
            <select class="form-input" style="width:150px;" name="role" onchange="this.form.submit()">
                <option value="">All roles</option>
                <option value="student" {{ request('role')==='student' ? 'selected' : '' }}>Students</option>
                <option value="faculty" {{ request('role')==='faculty' ? 'selected' : '' }}>Faculty</option>
                <option value="admin"   {{ request('role')==='admin'   ? 'selected' : '' }}>Admins</option>
            </select>
            <button type="submit" class="btn btn-ghost">Search</button>
        </form>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add User</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Department</th>
                <th>Joined</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
            <tr style="{{ $u->trashed() ? 'opacity:.5;' : '' }}">
                <td>
                    <div style="display:flex;align-items:center;gap:.65rem;">
                        <img src="{{ $u->avatar_url }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;" alt="">
                        <span style="font-weight:500;">{{ $u->full_name }}</span>
                    </div>
                </td>
                <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $u->email }}</td>
                <td>
                    <span class="badge {{ $u->role === 'admin' ? 'badge-review' : ($u->role === 'faculty' ? 'badge-revision' : 'badge-draft') }}" style="text-transform:capitalize;">{{ $u->role }}</span>
                </td>
                <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $u->department ?? '—' }}</td>
                <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $u->created_at->format('M d, Y') }}</td>
                <td>
                    @if($u->trashed())
                    <span class="badge badge-rejected">Deactivated</span>
                    @elseif($u->email_verified_at)
                    <span class="badge badge-approved">Active</span>
                    @else
                    <span class="badge badge-pending">Unverified</span>
                    @endif
                </td>
                <td>
                    @if($u->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.toggle', $u) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn {{ $u->trashed() ? 'btn-primary' : 'btn-danger' }} btn-sm"
                                onclick="return confirm('{{ $u->trashed() ? 'Reactivate' : 'Deactivate' }} this user?')">
                            {{ $u->trashed() ? 'Reactivate' : 'Deactivate' }}
                        </button>
                    </form>
                    @else
                    <span style="font-size:.75rem;color:var(--ink-mute);">You</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--ink-mute);">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);">
        {{ $users->links() }}
    </div>
</div>
@endsection