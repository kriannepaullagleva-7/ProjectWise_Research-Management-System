@extends('layouts.app')
@section('title', 'My Projects')
@section('page-title', 'My Projects')

@section('content')
<style>
    .mp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: .25rem .75rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .03em;
        white-space: nowrap;
    }
    .sp-pending      { background:#fef3c7; color:#92400e; }
    .sp-under_review { background:#e0e7ff; color:#3730a3; }
    .sp-approved     { background:#d1fae5; color:#065f46; }
    .sp-rejected     { background:#fee2e2; color:#7f1d1d; }
    .sp-draft        { background:#f1f5f9; color:#475569; }
    .mp-empty {
        padding: 4rem 2rem;
        text-align: center;
        color: #94a3b8;
    }
    .mp-empty svg { margin: 0 auto .875rem; display: block; opacity: .2; }
    .mp-row { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid #f3f4f6; transition: background .1s; }
    .mp-row:last-child { border-bottom: none; }
    .mp-row:hover { background: #f8fafc; }
    .mp-info { flex: 1; min-width: 0; }
    .mp-title { font-size: .9rem; font-weight: 600; color: #0f1117; text-decoration: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
    .mp-title:hover { color: #2563eb; }
    .mp-meta { font-size: .75rem; color: #94a3b8; margin-top: .2rem; display: flex; gap: .75rem; flex-wrap: wrap; }
    .mp-actions { display: flex; gap: .5rem; flex-shrink: 0; }
    .mp-btn { display: inline-flex; align-items: center; gap: .3rem; padding: .35rem .75rem; border-radius: 7px; font-size: .775rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; font-family: inherit; transition: background .1s; }
    .mp-btn-view  { background: #eff6ff; color: #1d4ed8; }
    .mp-btn-view:hover { background: #dbeafe; }
    .mp-btn-edit  { background: #f0fdf4; color: #15803d; }
    .mp-btn-edit:hover { background: #dcfce7; }
    .mp-btn-del   { background: #fef2f2; color: #dc2626; }
    .mp-btn-del:hover { background: #fee2e2; }
    @media (max-width: 640px) {
        .mp-row { flex-direction: column; align-items: flex-start; }
        .mp-actions { width: 100%; }
        .mp-btn { flex: 1; justify-content: center; }
    }
</style>

<div class="mp-header">
    <div>
        <p style="margin:0;color:#6b7280;font-size:.875rem;">{{ $projects->total() }} project(s) submitted</p>
    </div>
    <a href="{{ route('research.create') }}" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Submission
    </a>
</div>

<div class="card">
    @if($projects->isEmpty())
        <div class="mp-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            <p style="font-size:.95rem;font-weight:600;color:#64748b;margin:.5rem 0 .25rem;">No projects yet</p>
            <p style="font-size:.85rem;margin:0 0 1.25rem;">Submit your first research project to get started.</p>
            <a href="{{ route('research.create') }}" class="btn btn-primary btn-sm">Submit Research</a>
        </div>
    @else
        @foreach($projects as $project)
        <div class="mp-row">
            <div class="mp-info">
                <a href="{{ route('research.show', $project) }}" class="mp-title">{{ $project->title }}</a>
                <div class="mp-meta">
                    <span>{{ $project->category }}</span>
                    <span>·</span>
                    <span>{{ $project->created_at->format('M d, Y') }}</span>
                    @if($project->assignedFaculty)
                    <span>·</span>
                    <span>Reviewer: {{ $project->assignedFaculty->full_name ?? $project->assignedFaculty->name }}</span>
                    @endif
                    @if($project->file_path)
                    <span>·</span>
                    <span style="color:#2563eb;">📎 File attached</span>
                    @endif
                </div>
                @if($project->reviewer_feedback)
                <div style="margin-top:.4rem;font-size:.78rem;color:#6b7280;background:#f8fafc;border-left:3px solid #cbd5e1;padding:.3rem .65rem;border-radius:0 6px 6px 0;">
                    <strong>Feedback:</strong> {{ \Illuminate\Support\Str::limit($project->reviewer_feedback, 100) }}
                </div>
                @endif
            </div>

            <span class="status-pill sp-{{ $project->status }}">{{ $project->status_label }}</span>

            <div class="mp-actions">
                <a href="{{ route('research.show', $project) }}" class="mp-btn mp-btn-view">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    View
                </a>
                @if(in_array($project->status, ['pending', 'under_review', 'rejected']))
                <a href="{{ route('research.edit', $project) }}" class="mp-btn mp-btn-edit">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
                @endif
                @if(in_array($project->status, ['pending', 'rejected']))
                <form method="POST" action="{{ route('research.destroy', $project) }}" onsubmit="return confirm('Delete this project? This cannot be undone.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="mp-btn mp-btn-del">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                        Delete
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach

        @if($projects->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);">
            {{ $projects->links() }}
        </div>
        @endif
    @endif
</div>
@endsection
