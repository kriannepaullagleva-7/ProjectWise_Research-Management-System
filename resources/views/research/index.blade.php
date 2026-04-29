@extends('layouts.app')
@section('title', 'Research Explorer')
@section('page-title', 'Research Explorer')

@section('content')
<style>
    .search-bar {
        background: white;
        border-radius: 14px;
        border: 1px solid #f0f4f8;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .search-form {
        display: flex;
        gap: .75rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .search-field {
        flex: 1;
        min-width: 220px;
    }

    .form-label {
        display: block;
        font-size: .75rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .4rem;
        letter-spacing: .01em;
    }

    .search-input-wrap {
        position: relative;
    }

    .search-input-icon {
        position: absolute;
        left: .75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: .7rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 9px;
        font-size: .875rem;
        color: #111827;
        background: #fafafa;
        font-family: inherit;
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }

    .form-input.has-icon { padding-left: 2.25rem; }

    .form-input:focus,
    .form-select:focus {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }

    .form-input::placeholder { color: #9ca3af; }

    .search-actions {
        display: flex;
        gap: .5rem;
        align-items: flex-end;
        padding-bottom: 0;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #1d4ed8;
        color: white;
        padding: .7rem 1.25rem;
        border-radius: 9px;
        border: none;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        text-decoration: none;
        white-space: nowrap;
        transition: background .15s;
    }

    .btn-primary:hover { background: #1e40af; }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        color: #64748b;
        padding: .7rem .9rem;
        border-radius: 9px;
        border: 1.5px solid #e5e7eb;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        text-decoration: none;
        background: transparent;
        transition: background .15s;
    }

    .btn-ghost:hover { background: #f9fafb; color: #374151; }

    /* ── RESULTS GRID ── */
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .result-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #f0f4f8;
        display: flex;
        flex-direction: column;
        transition: box-shadow .2s, transform .2s, border-color .2s;
        overflow: hidden;
    }

    .result-card:hover {
        box-shadow: 0 8px 28px rgba(0,0,0,.08);
        transform: translateY(-3px);
        border-color: #e2e8f0;
    }

    .result-card-body {
        padding: 1.35rem 1.4rem;
        flex: 1;
    }

    .result-card-tags {
        display: flex;
        align-items: center;
        gap: .4rem;
        flex-wrap: wrap;
        margin-bottom: .875rem;
    }

    .badge-approved {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        background: #d1fae5;
        color: #065f46;
        padding: .22rem .65rem;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .02em;
        text-transform: uppercase;
    }

    .badge-approved::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #059669;
    }

    .cat-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: .22rem .65rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 500;
        border: 1px solid #e2e8f0;
    }

    .result-title {
        font-size: .9375rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.4;
        margin-bottom: .5rem;
        text-decoration: none;
        display: block;
    }

    .result-title:hover { color: #2563eb; }

    .result-abstract {
        font-size: .8125rem;
        color: #64748b;
        line-height: 1.65;
        margin-bottom: 1rem;
    }

    .result-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: .75rem;
        color: #94a3b8;
    }

    .result-meta-item {
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .result-card-footer {
        padding: .875rem 1.4rem;
        border-top: 1px solid #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafbfc;
    }

    .result-date {
        font-size: .75rem;
        color: #94a3b8;
    }

    .btn-view {
        font-size: .8rem;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        transition: gap .15s;
    }

    .btn-view:hover { gap: .45rem; }

    /* ── EMPTY STATE ── */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: #94a3b8;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        background: #f8fafc;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: #cbd5e1;
    }

    .empty-state h3 { font-size: .95rem; font-weight: 600; color: #64748b; margin-bottom: .35rem; }
    .empty-state p  { font-size: .85rem; }

    /* ── PAGINATION ── */
    .pagination-wrap {
        display: flex;
        justify-content: center;
    }
</style>

{{-- SEARCH BAR --}}
<div class="search-bar">
    <form method="GET" action="{{ route('research.index') }}" class="search-form">

        <div class="search-field">
            <label class="form-label">Search research</label>
            <div class="search-input-wrap">
                <svg class="search-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input class="form-input has-icon" type="text" name="search"
                       value="{{ request('search') }}"
                       placeholder="Title, keywords, abstract…">
            </div>
        </div>

        <div style="min-width:180px;">
            <label class="form-label">Category</label>
            <select class="form-select" name="category">
                <option value="">All categories</option>
                @foreach(($categories ?? []) as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div style="min-width:150px;">
            <label class="form-label">Sort by</label>
            <select class="form-select" name="sort">
                <option value="latest"    {{ request('sort','latest') === 'latest'    ? 'selected' : '' }}>Latest first</option>
                <option value="oldest"    {{ request('sort') === 'oldest'             ? 'selected' : '' }}>Oldest first</option>
                <option value="views"     {{ request('sort') === 'views'              ? 'selected' : '' }}>Most viewed</option>
                <option value="downloads" {{ request('sort') === 'downloads'          ? 'selected' : '' }}>Most downloaded</option>
            </select>
        </div>

        <div class="search-actions">
            <button type="submit" class="btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Search
            </button>
            @if(request()->hasAny(['search','category','sort']))
                <a href="{{ route('research.index') }}" class="btn-ghost">Clear</a>
            @endif
        </div>

    </form>
</div>

{{-- RESULTS --}}
@if($projects->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
        </div>
        <h3>No results found</h3>
        <p>Try different keywords or remove filters.</p>
    </div>
@else
    <div class="results-grid">
        @foreach($projects as $project)
            <div class="result-card">
                <div class="result-card-body">
                    <div class="result-card-tags">
                        <span class="badge-approved">Approved</span>
                        <span class="cat-badge">{{ $project->category }}</span>
                    </div>

                    <a href="{{ route('research.show', $project) }}" class="result-title">
                        {{ \Illuminate\Support\Str::limit($project->title, 80) }}
                    </a>

                    <p class="result-abstract">
                        {{ \Illuminate\Support\Str::limit($project->abstract, 130) }}
                    </p>

                    <div class="result-meta">
                        <div class="result-meta-item">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            {{ $project->user->full_name }}
                        </div>
                        <div class="result-meta-item">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            {{ number_format($project->views_count) }}
                        </div>
                        <div class="result-meta-item">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            {{ number_format($project->downloads_count) }}
                        </div>
                    </div>
                </div>

                <div class="result-card-footer">
                    <span class="result-date">
                        {{ $project->submission_date?->format('M d, Y') ?? $project->created_at->format('M d, Y') }}
                    </span>
                    <a href="{{ route('research.show', $project) }}" class="btn-view">
                        View
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination-wrap">
        {{ $projects->links() }}
    </div>
@endif
@endsection