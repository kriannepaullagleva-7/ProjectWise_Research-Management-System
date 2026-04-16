@extends('layouts.app')
@section('title', 'Research Explorer')
@section('page-title', 'Research Explorer')
 
@section('content')
{{-- Search + Filter Bar --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1.25rem 1.5rem;">
        <form method="GET" action="{{ route('research.index') }}" style="display:flex;gap:.75rem;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:220px;">
                <label class="form-label">Search research</label>
                <div style="position:relative;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:var(--ink-mute);pointer-events:none;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input class="form-input" style="padding-left:2.25rem;" type="text" name="search" value="{{ request('search') }}" placeholder="Title, keywords, abstract…">
                </div>
            </div>
            <div style="min-width:180px;">
                <label class="form-label">Category</label>
                <select class="form-input" name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:150px;">
                <label class="form-label">Sort by</label>
                <select class="form-input" name="sort">
                    <option value="latest" {{ request('sort','latest')==='latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort')==='oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="views" {{ request('sort')==='views' ? 'selected' : '' }}>Most viewed</option>
                    <option value="downloads" {{ request('sort')==='downloads' ? 'selected' : '' }}>Most downloaded</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding:.55rem 1.25rem;">Search</button>
            @if(request()->hasAny(['search','category','sort']))
            <a href="{{ route('research.index') }}" class="btn btn-ghost">Clear</a>
            @endif
        </form>
    </div>
</div>
 
{{-- Results --}}
@if($projects->isEmpty())
<div style="text-align:center;padding:4rem 2rem;color:var(--ink-mute);">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" style="margin:0 auto 1rem;display:block;opacity:.3;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    <p style="font-size:.9375rem;font-weight:500;color:var(--ink-soft);">No results found</p>
    <p style="font-size:.875rem;margin-top:.35rem;">Try different keywords or remove filters.</p>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.25rem;margin-bottom:1.5rem;">
    @foreach($projects as $project)
    <div class="card" style="display:flex;flex-direction:column;transition:box-shadow .15s,transform .15s;" onmouseover="this.style.boxShadow='0 8px 32px rgba(0,0,0,.08)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
        <div class="card-body" style="flex:1;">
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;flex-wrap:wrap;">
                <span class="badge badge-approved">Approved</span>
                <span style="font-size:.72rem;color:var(--ink-mute);background:var(--surface);padding:.15rem .55rem;border-radius:99px;border:1px solid var(--border);">{{ $project->category }}</span>
            </div>
            <h3 style="font-family:'DM Sans',sans-serif;font-size:.9375rem;font-weight:600;color:var(--ink);margin-bottom:.5rem;line-height:1.4;">
                <a href="{{ route('research.show', $project) }}" style="text-decoration:none;color:inherit;">{{ Str::limit($project->title, 80) }}</a>
            </h3>
            <p style="font-size:.8125rem;color:var(--ink-mute);line-height:1.6;margin-bottom:1rem;">{{ Str::limit($project->abstract, 130) }}</p>
            <div style="font-size:.75rem;color:var(--ink-mute);display:flex;gap:1rem;">
                <span>👤 {{ $project->user->full_name }}</span>
                <span>👁 {{ number_format($project->views_count) }}</span>
                <span>⬇ {{ number_format($project->downloads_count) }}</span>
            </div>
        </div>
        <div style="padding:.875rem 1.5rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:.75rem;color:var(--ink-mute);">{{ $project->submission_date?->format('M d, Y') ?? $project->created_at->format('M d, Y') }}</span>
            <a href="{{ route('research.show', $project) }}" class="btn btn-ghost btn-sm">View →</a>
        </div>
    </div>
    @endforeach
</div>
 
{{-- Pagination --}}
<div style="display:flex;justify-content:center;">
    {{ $projects->links() }}
</div>
@endif
@endsection