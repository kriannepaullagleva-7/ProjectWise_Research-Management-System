@extends('layouts.app')
@section('title', 'Faculty Review Queue')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="margin: 0; font-size: 1.875rem; font-weight: 700;">Review Queue</h1>
    <p style="margin: 0.5rem 0 0 0; color: #6b7280;">Manage and review all research project submissions</p>
</div>

<!-- Filter Section -->
<div style="background: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or student name" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Status</label>
            <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <button type="submit" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">Filter</button>
        @if(request()->hasAny(['search','status']))
        <a href="{{ route('faculty.explorer') }}" style="background: #e5e7eb; color: #374151; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600; text-align: center;">Clear</a>
        @endif
    </form>
</div>

<!-- Projects List -->
<div style="display: grid; gap: 1rem;">
    @if($projects->isEmpty())
    <div style="background: white; border-radius: 0.5rem; padding: 3rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
        <p style="color: #6b7280; margin: 0;">No projects to review match your filters.</p>
    </div>
    @else
    @foreach($projects as $project)
    @php
        $borderColor = $project->status === 'pending' ? '#f97316' : '#2563eb';
    @endphp
    <div style="background: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid {{ $borderColor }};">
        <div style="display: grid; grid-template-columns: 1fr auto; gap: 1.5rem; align-items: start;">
            <div>
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 600;">
                    <a href="{{ route('faculty.review', $project) }}" style="color: #2563eb; text-decoration: none;">
                        {{ $project->title }}
                    </a>
                </h3>
                <p style="margin: 0.5rem 0; color: #6b7280; font-size: 0.875rem;">
                    <strong>Student:</strong> {{ $project->user->full_name ?? $project->user->name }}
                </p>
                <p style="margin: 0.5rem 0; color: #6b7280; font-size: 0.875rem;">
                    <strong>Category:</strong> {{ $project->category }}
                </p>
                <p style="margin: 0.5rem 0; color: #6b7280; font-size: 0.875rem;">
                    <strong>Submitted:</strong> {{ $project->created_at->format('M d, Y H:i') }}
                    ({{ $project->created_at->diffForHumans() }})
                </p>
                <p style="margin: 1rem 0 0 0; color: #374151; line-height: 1.5;">
                    {{ \Illuminate\Support\Str::limit($project->description, 200) }}
                </p>
            </div>
            <div style="text-align: right;">
                @php
                    $statusColors = [
                        'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'under_review' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                        'approved' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                        'rejected' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'],
                    ];
                    $colors = $statusColors[$project->status] ?? ['bg' => '#e5e7eb', 'text' => '#374151'];
                @endphp
                <span style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 600; display: inline-block; margin-bottom: 1rem;">
                    {{ $project->status_label }}
                </span>
                <div>
                    <a href="{{ route('faculty.review', $project) }}" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-weight: 500; display: inline-block; margin-bottom: 0.5rem;">
                        Review
                    </a>
                </div>
                @if($project->reviews->count() > 0)
                <div style="font-size: 0.75rem; color: #6b7280;">
                    {{ $project->reviews->count() }} {{ \Illuminate\Support\Str::plural('review', $project->reviews->count()) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>

<!-- Pagination -->
@if($projects->hasPages())
<div style="margin-top: 2rem; display: flex; justify-content: center;">
    {{ $projects->links() }}
</div>
@endif
@endsection