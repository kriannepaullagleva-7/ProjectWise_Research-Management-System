@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<style>
    .ad-welcome {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .ad-welcome::before {
        content: '';
        position: absolute;
        right: -60px; top: -60px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(99,102,241,.2), transparent 70%);
        border-radius: 50%;
    }
    .ad-welcome h2 { font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: #fff; margin-bottom: .25rem; position: relative; z-index: 1; }
    .ad-welcome p  { font-size: .875rem; color: #94a3b8; position: relative; z-index: 1; margin: 0; }
    .ad-welcome-links { display: flex; gap: .75rem; position: relative; z-index: 1; flex-shrink: 0; }
    @media (max-width: 600px) {
        .ad-welcome { flex-direction: column; gap: 1rem; padding: 1.25rem; }
        .ad-welcome h2 { font-size: 1.2rem; }
        .ad-welcome-links { width: 100%; }
        .ad-btn { flex: 1; justify-content: center; text-align: center; }
    }
    .ad-btn {
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.15);
        color: #fff;
        padding: .55rem 1.125rem;
        border-radius: 9px;
        font-size: .8125rem;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
        white-space: nowrap;
    }
    .ad-btn:hover { background: rgba(255,255,255,.18); }
    .ad-stats {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    @media (max-width: 1100px) { .ad-stats { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 640px)  { .ad-stats { grid-template-columns: repeat(2, 1fr); } }
    .ad-stat {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 110px;
    }
    .ad-stat-label { font-size: .7rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: #94a3b8; }
    .ad-stat-val   { font-family: 'DM Serif Display', serif; font-size: 2rem; line-height: 1; align-self: flex-end; }
    @media (max-width: 640px) {
        .ad-stat { min-height: 90px; padding: .875rem 1rem; }
        .ad-stat-val { font-size: 1.65rem; }
        .ad-stat-label { font-size: .65rem; }
    }
    @media (max-width: 400px) {
        .ad-stat { min-height: 80px; padding: .75rem .875rem; }
        .ad-stat-val { font-size: 1.45rem; }
    }
    .ad-grid-2 { display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    @media (max-width: 900px) { .ad-grid-2 { grid-template-columns: 1fr; } }
    .ad-grid-2b { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media (max-width: 900px) { .ad-grid-2b { grid-template-columns: 1fr; } }
</style>

{{-- Welcome --}}
<div class="ad-welcome">
    <div>
        <h2>Admin Dashboard</h2>
        <p>System overview — {{ now()->format('l, F j Y') }}</p>
    </div>
    <div class="ad-welcome-links">
        <a href="{{ route('admin.users') }}" class="ad-btn">Manage Users</a>
        <a href="{{ route('admin.reports') }}" class="ad-btn">Reports</a>
    </div>
</div>

{{-- Stats --}}
<div class="ad-stats">
    @foreach([
        ['Total Projects',  $stats['total_projects'],  '#2563eb'],
        ['Approved',        $stats['approved'],         '#059669'],
        ['Pending',         $stats['pending_reviews'],  '#d97706'],
        ['Under Review',    $stats['under_review'],     '#7c3aed'],
        ['Total Users',     $stats['total_users'],      '#0f1117'],
        ['Faculty',         $stats['faculty'],          '#ea580c'],
    ] as [$label, $val, $color])
    <div class="ad-stat">
        <div class="ad-stat-label">{{ $label }}</div>
        <div class="ad-stat-val" style="color:{{ $color }};">{{ number_format($val) }}</div>
    </div>
    @endforeach
</div>

{{-- Charts row --}}
<div class="ad-grid-2">
    <div class="card">
        <div class="card-header"><h3>Monthly Submissions</h3></div>
        <div class="card-body"><canvas id="subChart" height="140"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Status Breakdown</h3></div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;">
            <canvas id="statusChart" width="180" height="180"></canvas>
        </div>
    </div>
</div>

{{-- Tables row --}}
<div class="ad-grid-2b">
    <div class="card">
        <div class="card-header">
            <h3>Recent Submissions</h3>
            <a href="{{ route('admin.reports') }}" class="btn btn-ghost btn-sm">View all</a>
        </div>
        <table class="table">
            <thead><tr><th>Title</th><th>Author</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recentProjects->take(8) as $p)
                <tr>
                    <td><a href="{{ route('research.show', $p) }}" style="color:var(--ink);text-decoration:none;font-weight:500;">{{ \Illuminate\Support\Str::limit($p->title, 36) }}</a></td>
                    <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $p->user->full_name ?? $p->user->name }}</td>
                    <td>
                        <span class="badge badge-{{ ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected','under_review'=>'review'][$p->status] ?? 'draft' }}">
                            {{ $p->status_label }}
                        </span>
                    </td>
                    <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $p->created_at->format('M d') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:1.5rem;color:var(--ink-mute);">No submissions yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Recent Users</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">Manage</a>
        </div>
        <table class="table">
            <thead><tr><th>Name</th><th>Role</th><th>Joined</th></tr></thead>
            <tbody>
                @forelse($recentUsers as $u)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.6rem;">
                            <div style="width:28px;height:28px;border-radius:50%;background:#2d5be3;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem;flex-shrink:0;">{{ strtoupper(substr($u->name,0,1)) }}</div>
                            <span style="font-weight:500;font-size:.875rem;">{{ $u->full_name ?? $u->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:.72rem;font-weight:600;text-transform:capitalize;padding:.2rem .6rem;border-radius:99px;
                            {{ $u->role==='admin' ? 'background:#dbeafe;color:#1e40af;' : ($u->role==='faculty' ? 'background:#fef3c7;color:#92400e;' : 'background:#e0e7ff;color:#3730a3;') }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $u->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:1.5rem;color:var(--ink-mute);">No users yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const subMonths = @json($submissionsChart->keys());
const subCounts = @json($submissionsChart->values());
const statusData = @json($statusBreakdown);

new Chart(document.getElementById('subChart'), {
    type: 'bar',
    data: {
        labels: subMonths,
        datasets: [{
            label: 'Submissions',
            data: subCounts,
            backgroundColor: '#2d5be3',
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { color: '#8a8f9e', font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { color: '#8a8f9e', font: { size: 11 } } }
        }
    }
});

const statusColors = {
    approved: '#059669', pending: '#d97706', rejected: '#dc2626',
    under_review: '#3b82f6', draft: '#9ca3af'
};
if (Object.keys(statusData).length) {
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: Object.keys(statusData).map(s => statusColors[s] ?? '#9ca3af'),
                borderWidth: 2, borderColor: '#fff',
            }]
        },
        options: {
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 10, padding: 12 } } }
        }
    });
}
</script>
@endpush
