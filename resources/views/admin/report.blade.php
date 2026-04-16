@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
<div style="display:flex;justify-content:flex-end;gap:.75rem;margin-bottom:1.5rem;">
    <a href="{{ route('admin.reports.export', ['type'=>'projects']) }}" class="btn btn-ghost">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export Projects PDF
    </a>
    <a href="{{ route('admin.reports.export', ['type'=>'users']) }}" class="btn btn-ghost">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export Users PDF
    </a>
</div>

{{-- Summary Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.75rem;">
    @foreach([
        ['Total Projects', $stats['total_projects']],
        ['Approved',       $stats['approved']],
        ['Total Downloads',$stats['total_downloads']],
        ['Total Views',    $stats['total_views']],
    ] as [$label, $val])
    <div class="stat-card">
        <div class="stat-label">{{ $label }}</div>
        <div class="stat-value">{{ number_format($val) }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
    {{-- Monthly Chart --}}
    <div class="card">
        <div class="card-header"><h3>Monthly Submissions (Last 12 Months)</h3></div>
        <div class="card-body"><canvas id="monthlyChart" height="120"></canvas></div>
    </div>

    {{-- Category Breakdown --}}
    <div class="card">
        <div class="card-header"><h3>By Category</h3></div>
        <div class="card-body" style="padding:1rem;">
            @foreach($categoryBreakdown as $row)
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.65rem;">
                <div style="flex:1;font-size:.8125rem;color:var(--ink-soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $row->category }}</div>
                <div style="flex:2;background:#f3f4f6;border-radius:99px;height:7px;overflow:hidden;">
                    <div style="height:100%;background:var(--accent);border-radius:99px;width:{{ min(100, ($row->count / max($categoryBreakdown->max('count'), 1)) * 100) }}%;"></div>
                </div>
                <div style="font-size:.8rem;font-weight:500;color:var(--ink);min-width:22px;text-align:right;">{{ $row->count }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top Projects --}}
<div class="card">
    <div class="card-header"><h3>Top Viewed Research Projects</h3></div>
    <table class="table">
        <thead><tr><th>#</th><th>Title</th><th>Author</th><th>Category</th><th>Views</th><th>Downloads</th></tr></thead>
        <tbody>
            @foreach($topProjects as $i => $p)
            <tr>
                <td style="font-family:'JetBrains Mono',monospace;font-size:.8rem;color:var(--ink-mute);">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</td>
                <td><a href="{{ route('research.show', $p) }}" style="font-weight:500;color:var(--ink);text-decoration:none;">{{ Str::limit($p->title, 55) }}</a></td>
                <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $p->user->full_name }}</td>
                <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $p->category }}</td>
                <td style="font-weight:500;">{{ number_format($p->views_count) }}</td>
                <td style="font-weight:500;">{{ number_format($p->downloads_count) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const months = @json($monthlySubmissions->pluck('month'));
const counts  = @json($monthlySubmissions->pluck('count'));
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Submissions',
            data: counts,
            borderColor: '#2d5be3',
            backgroundColor: 'rgba(45,91,227,.08)',
            fill: true,
            tension: .35,
            pointBackgroundColor: '#2d5be3',
            pointRadius: 4,
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
</script>
@endpush