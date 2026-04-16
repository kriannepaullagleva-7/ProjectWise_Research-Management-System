@extends('layouts.app')
@section('title', 'Student Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:1rem;margin-bottom:2rem;">
    <div style="background:linear-gradient(135deg, #ec4899 0%, #be123c 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
        <div style="font-size: 0.875rem; opacity: 0.9;">Total Projects</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['total_projects'] }}</div>
    </div>
    <div style="background:linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
        <div style="font-size: 0.875rem; opacity: 0.9;">Pending</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['pending'] }}</div>
    </div>
    <div style="background:linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
        <div style="font-size: 0.875rem; opacity: 0.9;">Under Review</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['under_review'] }}</div>
    </div>
    <div style="background:linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
        <div style="font-size: 0.875rem; opacity: 0.9;">Approved</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['approved'] }}</div>
    </div>
    <div style="background:linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
        <div style="font-size: 0.875rem; opacity: 0.9;">Rejected</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['rejected'] }}</div>
    </div>
</div>

<div style="background: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;">My Research Projects</h3>
        <a href="{{ route('research.create') }}" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 500;">+ New Project</a>
    </div>

    @if($myProjects->isEmpty())
    <div style="padding:3rem;text-align:center;color:#6b7280;font-size:.875rem;">
        <p>No projects yet. <a href="{{ route('research.create') }}" style="color: #2563eb;">Create your first project</a></p>
    </div>
    @else
    <div style="display: grid; gap: 1rem;">
        @foreach($myProjects as $project)
        @php
            $statusColors = [
                'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                'under_review' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                'approved' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                'rejected' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'],
            ];
            $colors = $statusColors[$project->status] ?? ['bg' => '#e5e7eb', 'text' => '#374151'];
        @endphp
        <div style="border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 0.5rem 0;">
                        <a href="{{ route('research.show', $project) }}" style="color: #2563eb; text-decoration: none;">
                            {{ $project->title }}
                        </a>
                    </h4>
                    <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">{{ \Illuminate\Support\Str::limit($project->description, 100) }}</p>
                    <p style="margin: 0.5rem 0 0 0; color: #9ca3af; font-size: 0.75rem;">{{ $project->category }} • {{ $project->created_at->format('M d, Y') }}</p>
                </div>
                <div style="margin-left: 1rem;">
                    <span style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; white-space: nowrap; display: inline-block;">
                        {{ $project->status_label }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection