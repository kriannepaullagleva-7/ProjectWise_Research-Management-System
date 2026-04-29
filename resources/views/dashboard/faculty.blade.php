@extends('layouts.app')
@section('title', 'Faculty Dashboard')
@section('page-title', 'Faculty Dashboard')

@section('content')
<style>
    .fc-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    @media (max-width: 900px) { .fc-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) {
        .fc-stat { min-height: 100px; padding: 1.1rem 1.25rem; }
        .fc-stat-val { font-size: 1.65rem; }
    }
    @media (max-width: 400px) {
        .fc-stats { grid-template-columns: 1fr 1fr; }
        .fc-stat { min-height: 85px; padding: .875rem 1rem; }
        .fc-stat-val { font-size: 1.45rem; }
    }

    .fc-stat {
        border-radius: 14px;
        padding: 1.35rem 1.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
    }
    .fc-stat::after {
        content: '';
        position: absolute;
        right: -20px; top: -20px;
        width: 90px; height: 90px;
        background: rgba(255,255,255,.12);
        border-radius: 50%;
    }
    .fc-stat-label  { font-size: .75rem; opacity: .9; font-weight: 500; }
    .fc-stat-val    { font-size: 2rem; font-weight: 700; line-height: 1; }
    .fc-stat-sub    { font-size: .72rem; opacity: .75; }
    .fc-stat-bottom { display: flex; align-items: flex-end; justify-content: space-between; }

    .fc-section { background: #fff; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 1.5rem; }
    .fc-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.125rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .fc-section-head h3 { font-family: 'DM Sans', sans-serif; font-size: .9375rem; font-weight: 700; color: #0f172a; margin: 0; }

    .fc-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f8fafc;
        transition: background .1s;
    }
    .fc-row:last-child { border-bottom: none; }
    .fc-row:hover { background: #f8fafc; }
    .fc-row-info { flex: 1; min-width: 0; }
    .fc-row-title { font-size: .875rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-decoration: none; display: block; margin-bottom: .15rem; }
    .fc-row-title:hover { color: #2563eb; }
    .fc-row-meta { font-size: .75rem; color: #94a3b8; display: flex; gap: .65rem; flex-wrap: wrap; }

    .sp-pill {
        display: inline-flex;
        padding: .2rem .65rem;
        border-radius: 99px;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .02em;
        white-space: nowrap;
    }
    .sp-pending      { background:#fef3c7; color:#92400e; }
    .sp-under_review { background:#e0e7ff; color:#3730a3; }
    .sp-approved     { background:#d1fae5; color:#065f46; }
    .sp-rejected     { background:#fee2e2; color:#7f1d1d; }

    .fc-review-btn {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        background: #2563eb;
        color: #fff;
        padding: .35rem .85rem;
        border-radius: 7px;
        font-size: .775rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: background .1s;
    }
    .fc-review-btn:hover { background: #1d4ed8; }

    .fc-empty { padding: 3rem 2rem; text-align: center; color: #94a3b8; }
    .fc-empty svg { margin: 0 auto .75rem; display: block; opacity: .2; }
    .fc-empty p { font-size: .9rem; color: #64748b; }
</style>

{{-- Stats --}}
<div class="fc-stats">
    <div class="fc-stat" style="background:linear-gradient(135deg,#f97316,#ea580c);">
        <div class="fc-stat-label">Pending Reviews</div>
        <div class="fc-stat-bottom">
            <div class="fc-stat-sub">Awaiting assignment</div>
            <div class="fc-stat-val">{{ $stats['pending_reviews'] }}</div>
        </div>
    </div>
    <div class="fc-stat" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
        <div class="fc-stat-label">Under Review</div>
        <div class="fc-stat-bottom">
            <div class="fc-stat-sub">In progress</div>
            <div class="fc-stat-val">{{ $stats['under_review'] }}</div>
        </div>
    </div>
    <div class="fc-stat" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);">
        <div class="fc-stat-label">Reviews Done</div>
        <div class="fc-stat-bottom">
            <div class="fc-stat-sub">Total completed</div>
            <div class="fc-stat-val">{{ $stats['reviewed_total'] }}</div>
        </div>
    </div>
    <div class="fc-stat" style="background:linear-gradient(135deg,#059669,#047857);">
        <div class="fc-stat-label">Approved</div>
        <div class="fc-stat-bottom">
            <div class="fc-stat-sub">Successfully approved</div>
            <div class="fc-stat-val">{{ $stats['approved'] }}</div>
        </div>
    </div>
</div>

{{-- Assigned Projects --}}
<div class="fc-section">
    <div class="fc-section-head">
        <h3>Submissions Assigned to You</h3>
        <a href="{{ route('faculty.explorer') }}" class="btn btn-primary btn-sm">Review Queue</a>
    </div>

    @if($assignedProjects->isEmpty())
        <div class="fc-empty">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p>No pending submissions assigned to you. Great work! 🎉</p>
        </div>
    @else
        @foreach($assignedProjects as $p)
        <div class="fc-row">
            <div class="fc-row-info">
                <a href="{{ route('research.show', $p) }}" class="fc-row-title">
                    {{ \Illuminate\Support\Str::limit($p->title, 60) }}
                </a>
                <div class="fc-row-meta">
                    <span>{{ $p->user->full_name ?? $p->user->name }}</span>
                    <span>·</span>
                    <span>{{ $p->category }}</span>
                    <span>·</span>
                    <span>{{ $p->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <span class="sp-pill sp-{{ $p->status }}">{{ $p->status_label }}</span>
            @if(in_array($p->status, ['pending','under_review']))
            <a href="{{ route('faculty.review', $p) }}" class="fc-review-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Review
            </a>
            @else
            <a href="{{ route('research.show', $p) }}" style="font-size:.775rem;color:#64748b;padding:.35rem .75rem;border:1px solid #e5e7eb;border-radius:7px;text-decoration:none;">View</a>
            @endif
        </div>
        @endforeach
    @endif
</div>

{{-- Quick Info --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <div class="fc-section">
        <div class="fc-section-head"><h3>Quick Stats</h3></div>
        <div style="padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:.65rem;font-size:.875rem;">
            <div style="display:flex;justify-content:space-between;">
                <span style="color:#6b7280;">Total Assigned</span>
                <span style="font-weight:600;color:#111827;">{{ $stats['total_submissions'] }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:#6b7280;">Approval Rate</span>
                <span style="font-weight:600;color:#059669;">
                    @if($stats['reviewed_total'] > 0)
                        {{ round(($stats['approved'] / $stats['reviewed_total']) * 100) }}%
                    @else
                        —
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="fc-section">
        <div class="fc-section-head"><h3>Quick Links</h3></div>
        <div style="padding:1rem 1.5rem;display:flex;flex-direction:column;gap:.5rem;">
            <a href="{{ route('faculty.explorer') }}" style="color:#2563eb;text-decoration:none;font-size:.875rem;padding:.35rem 0;">→ Review Queue</a>
            <a href="{{ route('research.index') }}" style="color:#2563eb;text-decoration:none;font-size:.875rem;padding:.35rem 0;">→ All Research</a>
            <a href="{{ route('profile.show') }}" style="color:#2563eb;text-decoration:none;font-size:.875rem;padding:.35rem 0;">→ My Profile</a>
        </div>
    </div>
</div>
@endsection
