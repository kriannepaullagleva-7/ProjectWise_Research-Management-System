@extends('layouts.app')
@section('title', 'Faculty Review Queue')

@push('styles')
<style>
    .filter-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: flex-end;
    }

    .project-card {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1.5rem;
        align-items: start;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .project-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .project-info h3 {
        margin: 0 0 0.75rem 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--ink);
    }

    .project-info a {
        color: var(--accent);
        text-decoration: none;
        transition: color 0.15s;
    }

    .project-info a:hover {
        color: var(--accent-h);
    }

    .project-meta {
        font-size: 0.875rem;
        color: var(--ink-soft);
        margin: 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .project-meta strong {
        color: var(--ink-mute);
        font-weight: 500;
    }

    .project-description {
        margin-top: 1rem;
        font-size: 0.875rem;
        color: var(--ink-soft);
        line-height: 1.6;
    }

    .project-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-end;
        justify-content: flex-start;
    }

    .review-count {
        font-size: 0.75rem;
        color: var(--ink-mute);
        text-align: right;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--ink-mute);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 1024px) {
        .filter-bar {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        .project-card {
            gap: 1.25rem;
            padding: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .filter-bar {
            grid-template-columns: 1fr;
        }
        .project-card {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .project-actions {
            align-items: stretch;
        }
        .project-actions button,
        .project-actions a {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="margin: 0; font-size: 1.875rem; font-weight: 700; color: var(--ink);">Review Queue</h1>
    <p style="margin: 0.5rem 0 0 0; color: var(--ink-mute);">Manage and review all research project submissions</p>
</div>

{{-- Filter Section --}}
<div class="card" style="margin-bottom: 2rem;">
    <form method="GET" class="filter-bar" style="padding: 1.5rem;">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or student name" class="form-input">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                Filter
            </button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('faculty.explorer') }}" class="btn btn-ghost">Clear</a>
            @endif
        </div>
    </form>
</div>

{{-- Projects List --}}
<div style="display: grid; gap: 1.25rem;">
    @if($projects->isEmpty())
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0.5rem;">No projects to review</p>
            <p style="font-size: 0.875rem; margin: 0;">No projects match your current filters. Try adjusting your search criteria.</p>
        </div>
    </div>
    @else
    @foreach($projects as $project)
    <div class="project-card">
        <div class="project-info">
            <h3>
                <a href="{{ route('faculty.review', $project) }}">
                    {{ $project->title }}
                </a>
            </h3>

            <div class="project-meta">
                <strong>Student:</strong>
                {{ $project->user->full_name ?? $project->user->name }}
            </div>

            <div class="project-meta">
                <strong>Category:</strong>
                {{ $project->category }}
            </div>

            <div class="project-meta">
                <strong>Submitted:</strong>
                <span>{{ $project->created_at->format('M d, Y') }} ({{ $project->created_at->diffForHumans() }})</span>
            </div>

            @if($project->abstract || $project->description)
            <div class="project-description">
                {{ \Illuminate\Support\Str::limit($project->description ?? $project->abstract, 200) }}
            </div>
            @endif
        </div>

        <div class="project-actions">
            {{-- Status Badge --}}
            <span class="badge badge-{{ ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected','under_review'=>'review','revision_needed'=>'revision','draft'=>'draft'][$project->status] ?? 'draft' }}" style="font-weight: 600;">
                {{ $project->status_label }}
            </span>

            {{-- Review Button --}}
            <a href="{{ route('faculty.review', $project) }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Review
            </a>

            {{-- Review Count --}}
            @if($project->reviews->count() > 0)
            <div class="review-count">
                {{ $project->reviews->count() }} {{ \Illuminate\Support\Str::plural('review', $project->reviews->count()) }}
            </div>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>

{{-- Pagination --}}
@if($projects->hasPages())
<div style="margin-top: 2rem; display: flex; justify-content: center;">
    {{ $projects->links() }}
</div>
@endif
@endsection