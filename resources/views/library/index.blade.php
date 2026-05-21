@extends('layouts.app')
@section('title', 'Saved Library')
@section('page-title', 'My Saved Library')

@section('content')
<style>
    .lib-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .lib-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: box-shadow .2s, transform .2s;
    }
    .lib-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.08); transform: translateY(-2px); }
    .lib-card-body { padding: 1.25rem 1.35rem; flex: 1; display: flex; flex-direction: column; gap: .75rem; }
    .lib-card-footer {
        padding: .875rem 1.35rem;
        border-top: 1px solid var(--border);
        background: var(--surface);
        display: flex;
        gap: .5rem;
    }
    .lib-title { font-size: .9375rem; font-weight: 700; color: var(--ink); text-decoration: none; line-height: 1.4; display: block; }
    .lib-title:hover { color: var(--accent); }
    .lib-author { font-size: .8rem; color: var(--ink-mute); }
    .lib-abstract { font-size: .8125rem; color: var(--ink-soft); line-height: 1.65; flex: 1; }
    .lib-tags { display: flex; gap: .4rem; flex-wrap: wrap; align-items: center; }
    .lib-stats { display: flex; gap: 1rem; font-size: .75rem; color: var(--ink-mute); padding-top: .65rem; border-top: 1px solid #f3f4f6; }
    .lib-stat { display: flex; align-items: center; gap: .3rem; }

    .sp-pending      { background:#fef3c7; color:#92400e; }
    .sp-under_review { background:#e0e7ff; color:#3730a3; }
    .sp-approved     { background:#d1fae5; color:#065f46; }
    .sp-rejected     { background:#fee2e2; color:#7f1d1d; }

    .lib-empty {
        text-align: center;
        padding: 5rem 2rem;
    }
    .lib-empty-icon {
        width: 64px; height: 64px;
        background: var(--surface);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.25rem;
        color: var(--ink-mute);
    }
</style>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem;">
    <div>
        <p style="margin:0;color:var(--ink-soft);font-size:.875rem;">Your saved research collection</p>
        @if($saved->total() > 0)
        <p style="margin:.25rem 0 0;color:var(--ink-mute);font-size:.8rem;">{{ $saved->total() }} item(s) saved</p>
        @endif
    </div>
    <a href="{{ route('research.index') }}" class="btn btn-ghost btn-sm">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        Browse Research
    </a>
</div>

@if($saved->isEmpty())
<div class="card">
    <div class="lib-empty">
        <div class="lib-empty-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
        </div>
        <h3 style="font-size:.95rem;font-weight:600;color:var(--ink-soft);margin:0 0 .35rem;">No saved items yet</h3>
        <p style="font-size:.85rem;color:var(--ink-mute);margin:0 0 1.25rem;">Explore research and save papers to build your personal library.</p>
        <a href="{{ route('research.index') }}" class="btn btn-primary">Browse Research</a>
    </div>
</div>
@else

<div class="lib-grid">
    @foreach($saved as $item)
    @php $project = $item->researchProject; @endphp
    <div class="lib-card">
        <div class="lib-card-body">
            <div class="lib-tags">
                <span class="sp-{{ $project->status }}" style="display:inline-flex;align-items:center;padding:.2rem .65rem;border-radius:99px;font-size:.68rem;font-weight:700;letter-spacing:.02em;text-transform:capitalize;">
                    {{ str_replace('_',' ', $project->status) }}
                </span>
                <span style="background:var(--surface);color:var(--ink-mute);border:1px solid var(--border);padding:.2rem .65rem;border-radius:99px;font-size:.7rem;">
                    {{ $project->category ?? 'Uncategorized' }}
                </span>
            </div>

            <div>
                <a href="{{ route('research.show', $project) }}" class="lib-title">
                    {{ \Illuminate\Support\Str::limit($project->title, 70) }}
                </a>
                <div class="lib-author" style="margin-top:.25rem;">
                    {{ $project->user->full_name ?? $project->user->name }}
                </div>
            </div>

            <p class="lib-abstract">
                {{ \Illuminate\Support\Str::limit($project->description ?? $project->abstract ?? 'No description available.', 120) }}
            </p>

            <div class="lib-stats">
                <div class="lib-stat">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    {{ number_format($project->views_count) }}
                </div>
                <div class="lib-stat">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    {{ number_format($project->downloads_count) }}
                </div>
                <div class="lib-stat" style="margin-left:auto;">
                    {{ $project->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>

        <div class="lib-card-footer">
            <a href="{{ route('research.show', $project) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">View</a>
            @if($project->file_path)
            <a href="{{ route('research.download', $project) }}" class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;">Download</a>
            @endif
            <form method="POST" action="{{ route('saved.toggle', $project) }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" title="Remove from saved">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

@if($saved->hasPages())
<div style="display:flex;justify-content:center;">
    {{ $saved->links() }}
</div>
@endif
@endif
@endsection
