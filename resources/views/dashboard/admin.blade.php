<!-- @extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
{{-- Stats Grid --}}
<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;margin-bottom:2rem;">
    @foreach([
        ['Total Projects', $stats['total_projects'], '#0f1117'],
        ['Approved', $stats['approved'], '#059669'],
        ['Pending', $stats['pending'], '#d97706'],
        ['Total Users', $stats['total_users'], '#0f1117'],
        ['Students', $stats['students'], '#2d5be3'],
        ['Faculty', $stats['faculty'], '#7c3aed'],
    ] as [$label, $val, $color])
    <div class="stat-card">
        <div class="stat-label">{{ $label }}</div>
        <div class="stat-value" style="color:{{ $color }};">{{ $val }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1.75fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
    {{-- Submissions Chart --}}
    <div class="card">
        <div class="card-header"><h3>Submissions (Last 6 Months)</h3></div>
        <div class="card-body" style="padding-top:1rem;">
            <canvas id="submissionsChart" height="140"></canvas>
        </div>
    </div>

    {{-- Status Breakdown --}}
    <div class="card">
        <div class="card-header"><h3>Status Breakdown</h3></div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;">
            <canvas id="statusChart" width="180" height="180"></canvas>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    {{-- Recent Projects --}}
    <div class="card">
        <div class="card-header">
            <h3>Recent Submissions</h3>
            <a href="{{ route('research.index') }}" class="btn btn-ghost btn-sm">View all</a>
        </div>
        <table class="table">
            <thead><tr><th>Title</th><th>By</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @foreach($recentProjects->take(8) as $p)
                <tr>
                    <td><a href="{{ route('research.show', $p) }}" style="color:var(--ink);text-decoration:none;font-weight:500;">{{ Str::limit($p->title, 38) }}</a></td>
                    <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $p->user->full_name }}</td>
                    <td><span class="badge badge-{{ ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected','under_review'=>'review','revision_needed'=>'revision','draft'=>'draft'][$p->status] ?? 'draft' }}">{{ $p->status_label }}</span></td>
                    <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $p->created_at->format('M d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Recent Users --}}
    <div class="card">
        <div class="card-header">
            <h3>Recent Users</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">Manage</a>
        </div>
        <table class="table">
            <thead><tr><th>Name</th><th>Role</th><th>Joined</th></tr></thead>
            <tbody>
                @foreach($recentUsers as $u)
                <tr>
                    <td style="display:flex;align-items:center;gap:.6rem;">
                        <img src="{{ $u->avatar_url }}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;" alt="">
                        <span style="font-weight:500;">{{ $u->full_name }}</span>
                    </td>
                    <td><span class="badge {{ $u->role === 'admin' ? 'badge-review' : ($u->role === 'faculty' ? 'badge-revision' : 'badge-draft') }}" style="text-transform:capitalize;">{{ $u->role }}</span></td>
                    <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $u->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const months = @json($submissionsChart->keys());
const counts  = @json($submissionsChart->values());
const statusData  = @json($statusBreakdown);

new Chart(document.getElementById('submissionsChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Submissions',
            data: counts,
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

const statusColors = { approved:'#059669', pending:'#d97706', rejected:'#dc2626', under_review:'#3b82f6', revision_needed:'#f97316', draft:'#9ca3af' };
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: Object.keys(statusData).map(s => statusColors[s] ?? '#9ca3af'),
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 10, padding: 12 } }
        }
    }
});
</script>
@endpush -->