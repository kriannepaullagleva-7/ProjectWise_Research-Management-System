@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
<style>
    .rpt-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.75rem; }
    .rpt-charts { display:grid; grid-template-columns:2fr 1fr; gap:1.5rem; margin-bottom:1.5rem; }
    @media (max-width: 900px) { .rpt-charts { grid-template-columns: 1fr; } }
    @media (max-width: 700px) { .rpt-stats { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 400px) { .rpt-stats { grid-template-columns: 1fr 1fr; } }
</style>

<div style="display:flex;justify-content:flex-end;gap:.75rem;margin-bottom:1.5rem;">
    <a href="{{ route('admin.reports.export') }}" class="btn btn-ghost">
        Export CSV
    </a>
</div>

{{-- Summary Stats --}}
<div class="rpt-stats">
    @php
        $stats = $stats ?? [];
    @endphp

    @foreach([
        ['Total Projects', $stats['total_projects'] ?? 0],
        ['Approved', $stats['approved'] ?? 0],
        ['Total Downloads', $stats['total_downloads'] ?? 0],
        ['Total Views', $stats['total_views'] ?? 0],
    ] as [$label, $val])
        <div class="stat-card">
            <div class="stat-label">{{ $label }}</div>
            <div class="stat-value">{{ number_format((int)$val) }}</div>
        </div>
    @endforeach
</div>

<div class="rpt-charts">

    {{-- Monthly Chart --}}
    <div class="card">
        <div class="card-header"><h3>Monthly Submissions (Last 12 Months)</h3></div>
        <div class="card-body">
            <canvas id="monthlyChart" height="120"></canvas>
        </div>
    </div>

    {{-- Category Breakdown --}}
    <div class="card">
        <div class="card-header"><h3>By Category</h3></div>
        <div class="card-body" style="padding:1rem;">

            @php
                $categoryBreakdown = $categoryBreakdown ?? collect();
                $maxCount = $categoryBreakdown->max('count') ?? 1;
            @endphp

            @forelse($categoryBreakdown as $row)
                @php
                    $percentage = $maxCount > 0 ? min(100, ($row->count / $maxCount) * 100) : 0;
                @endphp

                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.65rem;">
                    <div style="flex:1;font-size:.8125rem;color:var(--ink-soft);overflow:hidden;">
                        {{ $row->category ?? 'N/A' }}
                    </div>

                    <div style="flex:2;background:#f3f4f6;border-radius:99px;height:7px;overflow:hidden;">
                        <div style="height:100%;background:var(--accent);border-radius:99px;width:{{ $percentage }}%"></div>
                    </div>

                    <div style="font-size:.8rem;font-weight:500;min-width:22px;text-align:right;">
                        {{ $row->count ?? 0 }}
                    </div>
                </div>

            @empty
                <p style="color:#9ca3af;text-align:center;">No data available</p>
            @endforelse

        </div>
    </div>
</div>

{{-- Top Projects --}}
<div class="card">
    <div class="card-header"><h3>Top Viewed Research Projects</h3></div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Views</th>
                <th>Downloads</th>
            </tr>
        </thead>

        <tbody>
        @php
            $topProjects = $topProjects ?? collect();
        @endphp

        @forelse($topProjects as $i => $p)
            <tr>
                <td>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>

                <td>
                    <a href="{{ route('research.show', $p->id ?? 0) }}">
                        {{ \Illuminate\Support\Str::limit($p->title ?? 'Untitled', 55) }}
                    </a>
                </td>

                <td>
                    {{ optional($p->user)->full_name 
                        ?? optional($p->user)->name 
                        ?? 'Unknown' }}
                </td>

                <td>{{ $p->category ?? 'N/A' }}</td>

                <td>{{ number_format($p->views_count ?? 0) }}</td>
                <td>{{ number_format($p->downloads_count ?? 0) }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:2rem;">
                    No projects yet
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // const months = @json($monthlySubmissions?->pluck('month')->values() ?? []);
    // const counts = @json($monthlySubmissions?->pluck('count')->values() ?? []);

    const canvas = document.getElementById('monthlyChart');

    if (canvas && months.length) {
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Submissions',
                    data: counts,
                    borderColor: '#2d5be3',
                    backgroundColor: 'rgba(45,91,227,.08)',
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true },
                    x: { display: true }
                }
            }
        });
    }
});
</script>
@endpush