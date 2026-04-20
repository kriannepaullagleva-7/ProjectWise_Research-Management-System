@extends('layouts.app')
@section('title', 'My Saved Research')
@section('page-title', 'My Saved Research Library')

@section('content')
<div style="margin-bottom: 2rem;">
    <p style="color: #6b7280; margin: 0;">Your collection of saved research projects</p>
</div>

@if($projects->isEmpty())
<div style="background: white; border-radius: 0.5rem; padding: 3rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
    <p style="color: #6b7280; margin: 0; font-size: 1.125rem;">No saved items yet</p>
    <p style="color: #9ca3af; margin: 0.5rem 0 0 0;">Explore research and save items to your library</p>
    <a href="{{ route('research.index') }}" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600; display: inline-block; margin-top: 1rem;">Browse Research</a>
</div>
@else
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
    @foreach($projects as $project)
    <div style="background: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: box-shadow 0.2s; border: 1px solid #e5e7eb;">
        <div style="padding: 1.5rem;">
            <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1rem;">
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 600;">
                        <a href="{{ route('research.show', $project) }}" style="color: #2563eb; text-decoration: none;">
                            {{ \Illuminate\Support\Str::limit($project->title, 50) }}
                        </a>
                    </h3>
                    <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">{{ $project->user->full_name ?? $project->user->name }}</p>
                </div>
                <form method="POST" action="{{ route('saved.toggle', $project) }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1.25rem; padding: 0;" title="Remove from saved">❤️</button>
                </form>
            </div>

            <p style="margin: 0 0 1rem 0; color: #374151; font-size: 0.875rem; line-height: 1.6;">
                {{ \Illuminate\Support\Str::limit($project->description, 100) }}
            </p>

            @php
                $statusColors = [
                    'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                    'under_review' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                    'approved' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                    'rejected' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'],
                ];
                $colors = $statusColors[$project->status] ?? ['bg' => '#e5e7eb', 'text' => '#374151'];
            @endphp

            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                <span style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.7rem;">
                    {{ $project->category }}
                </span>
            </div>

            <a href="{{ route('research.show', $project) }}" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-weight: 500; display: inline-block; font-size: 0.875rem;">
                View Project
            </a>
        </div>
    </div>
    @endforeach
</div>

@if($projects->hasPages())
<div style="margin-top: 2rem; display: flex; justify-content: center;">
    {{ $projects->links() }}
</div>
@endif
@endif
@endsection
