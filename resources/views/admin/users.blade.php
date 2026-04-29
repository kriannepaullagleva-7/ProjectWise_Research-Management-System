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
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:.65rem;">
                        <div style="width:32px;height:32px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-weight:600;color:#6b7280;">{{ substr($u->name, 0, 1) }}</div>
                        <span style="font-weight:500;">{{ $u->full_name ?? $u->name }}</span>
                    </div>
                </td>
                <td style="font-size:.8125rem;color:#6b7280;">{{ $u->email }}</td>
                <td>
                    @if($u->role === 'admin')
                        <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; text-transform: capitalize;">{{ $u->role }}</span>
                    @elseif($u->role === 'faculty')
                        <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; text-transform: capitalize;">{{ $u->role }}</span>
                    @else
                        <span style="background: #e0e7ff; color: #3730a3; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; text-transform: capitalize;">{{ $u->role }}</span>
                    @endif
                </td>
                <td style="font-size:.8125rem;color:#6b7280;">{{ $u->department ?? '—' }}</td>
                <td style="font-size:.8125rem;color:#6b7280;">{{ $u->created_at->format('M d, Y') }}</td>
                <td>
                    @if($u->email_verified_at)
                    <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500;">Active</span>
                    @else
                    <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500;">Unverified</span>
                    @endif
                </td>
                <td>
                    @if($u->id !== auth()->id())
                    <div style="display:flex;gap:.5rem;">
                        <form method="POST" action="{{ route('admin.users.toggle', $u) }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background: #fee2e2; color: #7f1d1d; padding: 0.35rem 0.75rem; border: none; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500; cursor: pointer;" onclick="return confirm('Are you sure?')">
                                Toggle
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.delete', $u) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #dc2626; color: #fff; padding: 0.35rem 0.75rem; border: none; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500; cursor: pointer;" onclick="return confirm('Delete this user permanently? This action cannot be undone.')">
                                Delete
                            </button>
                        </form>
                    </div>
                    @else
                    <span style="font-size:.75rem;color:#6b7280;">Current</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:2rem;color:#6b7280;">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.5rem;border-top:1px solid #e5e7eb;">
        {{ $users->links() }}
    </div>
</div>
@endsection
