@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap');

    .db-root { font-family: 'DM Sans', sans-serif; }

    /* ── WELCOME BAR ── */
    .db-welcome {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .db-welcome::before {
        content: '';
        position: absolute;
        right: -60px;
        top: -60px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(99,102,241,.25), transparent 70%);
        border-radius: 50%;
    }

    .db-welcome::after {
        content: '';
        position: absolute;
        right: 120px;
        bottom: -40px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(37,99,235,.2), transparent 70%);
        border-radius: 50%;
    }

    .db-welcome-text { position: relative; z-index: 2; }

    .db-welcome-text h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: .25rem;
    }

    .db-welcome-text p { font-size: .875rem; color: #94a3b8; }

    .db-welcome-action {
        position: relative;
        z-index: 2;
        background: rgba(255,255,255,.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,.15);
        color: #fff;
        padding: .65rem 1.25rem;
        border-radius: 10px;
        font-size: .85rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        transition: background .15s;
        white-space: nowrap;
    }

    .db-welcome-action:hover { background: rgba(255,255,255,.18); }

    /* ── STATS ── */
    .db-stats {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    @media (max-width: 900px) { .db-stats { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 600px) { .db-stats { grid-template-columns: repeat(2, 1fr); } }

    .db-stat {
        background: #fff;
        border-radius: 14px;
        padding: 1.25rem 1.4rem;
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
        transition: box-shadow .15s, transform .15s;
    }

    .db-stat:hover { box-shadow: 0 8px 25px rgba(0,0,0,.08); transform: translateY(-2px); }

    .db-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: .85rem;
        font-size: 1.1rem;
    }

    .db-stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: .25rem;
    }

    .db-stat-label {
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .db-stat-accent {
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        border-radius: 0 14px 0 60px;
        opacity: .08;
    }

    .s-total   { --c: #2563eb; }
    .s-pending { --c: #f59e0b; }
    .s-review  { --c: #6366f1; }
    .s-approved{ --c: #059669; }
    .s-rejected{ --c: #ef4444; }

    .db-stat .db-stat-icon { background: color-mix(in srgb, var(--c) 12%, transparent); }
    .db-stat .db-stat-num  { color: var(--c); }
    .db-stat .db-stat-label{ color: color-mix(in srgb, var(--c) 60%, #64748b); }
    .db-stat .db-stat-accent { background: var(--c); }

    /* ── PROJECTS TABLE ── */
    .db-section {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .db-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .db-section-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .db-btn-new {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #2563eb;
        color: #fff;
        padding: .5rem 1rem;
        border-radius: 8px;
        font-size: .8125rem;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s, transform .1s;
    }

    .db-btn-new:hover { background: #1d4ed8; transform: translateY(-1px); }

    .db-empty {
        padding: 3.5rem 2rem;
        text-align: center;
        color: #94a3b8;
    }

    .db-empty svg { margin: 0 auto .75rem; display: block; opacity: .25; }
    .db-empty p { font-size: .9rem; color: #64748b; font-weight: 500; }
    .db-empty a { color: #2563eb; font-weight: 600; text-decoration: none; }

    /* Project rows */
    .db-project {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f8fafc;
        transition: background .1s;
        gap: 1rem;
    }

    .db-project:last-child { border-bottom: none; }
    .db-project:hover { background: #f8fafc; }

    .db-project-info { flex: 1; min-width: 0; }

    .db-project-title {
        font-size: .9rem;
        font-weight: 600;
        color: #0f172a;
        text-decoration: none;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: .2rem;
    }

    .db-project-title:hover { color: #2563eb; }

    .db-project-meta {
        font-size: .75rem;
        color: #94a3b8;
        display: flex;
        gap: .75rem;
        align-items: center;
    }

    .db-badge {
        display: inline-flex;
        align-items: center;
        padding: .25rem .7rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .03em;
        white-space: nowrap;
    }

    .badge-pending       { background: #fef3c7; color: #92400e; }
    .badge-under_review  { background: #e0e7ff; color: #3730a3; }
    .badge-approved      { background: #d1fae5; color: #065f46; }
    .badge-rejected      { background: #fee2e2; color: #7f1d1d; }
    .badge-draft         { background: #f1f5f9; color: #475569; }
    .badge-revision_needed { background: #fef9c3; color: #713f12; }

    .db-view-btn {
        font-size: .75rem;
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        padding: .3rem .7rem;
        border: 1px solid #dbeafe;
        border-radius: 6px;
        transition: background .1s;
    }

    .db-view-btn:hover { background: #eff6ff; }
</style>

<div class="db-root">

    {{-- WELCOME --}}
    <div class="db-welcome">
        <div class="db-welcome-text">
            <h2>Good day, {{ auth()->user()->first_name ?? auth()->user()->name }} 👋</h2>
            <p>Here's an overview of your research activity.</p>
        </div>
        <a href="{{ route('research.create') }}" class="db-welcome-action">+ New Project</a>
    </div>

    {{-- STATS --}}
    <div class="db-stats">
        <div class="db-stat s-total">
            <div class="db-stat-accent"></div>
            <div class="db-stat-icon">📁</div>
            <div class="db-stat-num">{{ $stats['total_projects'] }}</div>
            <div class="db-stat-label">Total Projects</div>
        </div>
        <div class="db-stat s-pending">
            <div class="db-stat-accent"></div>
            <div class="db-stat-icon">⏳</div>
            <div class="db-stat-num">{{ $stats['pending'] }}</div>
            <div class="db-stat-label">Pending</div>
        </div>
        <div class="db-stat s-review">
            <div class="db-stat-accent"></div>
            <div class="db-stat-icon">🔍</div>
            <div class="db-stat-num">{{ $stats['under_review'] }}</div>
            <div class="db-stat-label">Under Review</div>
        </div>
        <div class="db-stat s-approved">
            <div class="db-stat-accent"></div>
            <div class="db-stat-icon">✅</div>
            <div class="db-stat-num">{{ $stats['approved'] }}</div>
            <div class="db-stat-label">Approved</div>
        </div>
        <div class="db-stat s-rejected">
            <div class="db-stat-accent"></div>
            <div class="db-stat-icon">❌</div>
            <div class="db-stat-num">{{ $stats['rejected'] }}</div>
            <div class="db-stat-label">Rejected</div>
        </div>
    </div>

    {{-- PROJECTS --}}
    <div class="db-section">
        <div class="db-section-header">
            <h3>My Research Projects</h3>
            <a href="{{ route('research.create') }}" class="db-btn-new">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Project
            </a>
        </div>

        @if($myProjects->isEmpty())
            <div class="db-empty">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                <p>No projects yet.</p>
                <a href="{{ route('research.create') }}">Submit your first research →</a>
            </div>
        @else
            @foreach($myProjects as $project)
                <div class="db-project">
                    <div class="db-project-info">
                        <a href="{{ route('research.show', $project) }}" class="db-project-title">
                            {{ $project->title }}
                        </a>
                        <div class="db-project-meta">
                            <span>{{ $project->category }}</span>
                            <span>·</span>
                            <span>{{ $project->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <span class="db-badge badge-{{ $project->status }}">{{ $project->status_label }}</span>
                    <a href="{{ route('research.show', $project) }}" class="db-view-btn">View →</a>
                </div>
            @endforeach
        @endif
    </div>

</div>
@endsection