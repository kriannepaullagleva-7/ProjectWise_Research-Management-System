@extends('layouts.app')
@section('title', 'Activity Log')
@section('page-title', 'Activity Log')

@section('content')
<style>
    .act-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
    @media (max-width: 768px) { .act-grid { grid-template-columns: 1fr; } }
</style>
<div class="act-grid">

    <div class="card">
        <div class="card-header">
            <h3>Recent User Registrations</h3>
        </div>
        <table class="table">
            <thead><tr><th>Name</th><th>Role</th><th>Joined</th></tr></thead>
            <tbody>
                @forelse($recentUsers as $u)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.55rem;">
                            <div style="width:28px;height:28px;border-radius:50%;background:#2d5be3;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.72rem;flex-shrink:0;">{{ strtoupper(substr($u->name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:500;font-size:.875rem;">{{ $u->full_name ?? $u->name }}</div>
                                <div style="font-size:.72rem;color:var(--ink-mute);">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:.7rem;font-weight:600;text-transform:capitalize;padding:.2rem .6rem;border-radius:99px;
                            {{ $u->role==='admin' ? 'background:#dbeafe;color:#1e40af;' : ($u->role==='faculty' ? 'background:#fef3c7;color:#92400e;' : 'background:#e0e7ff;color:#3730a3;') }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td style="font-size:.8rem;color:var(--ink-mute);">{{ $u->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:2rem;color:var(--ink-mute);">No users yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Recent Project Submissions</h3>
        </div>
        <table class="table">
            <thead><tr><th>Title</th><th>Author</th><th>Status</th><th>When</th></tr></thead>
            <tbody>
                @forelse($recentProjects as $p)
                <tr>
                    <td>
                        <a href="{{ route('research.show', $p) }}" style="font-weight:500;color:var(--ink);text-decoration:none;font-size:.875rem;">
                            {{ \Illuminate\Support\Str::limit($p->title, 35) }}
                        </a>
                    </td>
                    <td style="font-size:.8rem;color:var(--ink-mute);">{{ $p->user->full_name ?? $p->user->name }}</td>
                    <td>
                        <span class="badge badge-{{ ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected','under_review'=>'review'][$p->status] ?? 'draft' }}">
                            {{ $p->status_label }}
                        </span>
                    </td>
                    <td style="font-size:.8rem;color:var(--ink-mute);">{{ $p->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--ink-mute);">No submissions yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
