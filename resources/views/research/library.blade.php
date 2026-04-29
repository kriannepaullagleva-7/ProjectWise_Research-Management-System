@extends('layouts.app')
@section('title', 'My Saved Research')
@section('page-title', 'My Saved Research Library')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <p style="color: #6b7280; margin: 0; font-size: 1rem;">Your collection of saved research projects</p>
        @if(!$projects->isEmpty())
        <p style="color: #9ca3af; margin: 0.5rem 0 0 0; font-size: 0.875rem;">{{ $projects->total() }} item(s) in your library</p>
        @endif
    </div>
</div>

@if($projects->isEmpty())
<div style="background: white; border-radius: 0.5rem; padding: 3rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
    <div style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
    <p style="color: #6b7280; margin: 0; font-size: 1.125rem;">No saved items yet</p>
    <p style="color: #9ca3af; margin: 0.5rem 0 0 0;">Explore research and save items to your library</p>
    <a href="{{ route('research.index') }}" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600; display: inline-block; margin-top: 1rem; transition: background 0.2s;">Browse Research</a>
</div>
@else
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
    @foreach($projects as $project)
    @php
        $statusColors = [
            'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
            'under_review' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
            'approved' => ['bg' => '#d1fae5', 'text' => '#065f46'],
            'rejected' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'],
        ];
        $colors = $statusColors[$project->status] ?? ['bg' => '#e5e7eb', 'text' => '#374151'];
    @endphp
    <div style="background: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; transition: box-shadow 0.2s, transform 0.2s; display: flex; flex-direction: column;">
        <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
            {{-- Header with title and unsave button --}}
            <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1rem;">
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 600; line-height: 1.5;">
                        <a href="{{ route('research.show', $project) }}" style="color: #2563eb; text-decoration: none; transition: color 0.2s;">
                            {{ \Illuminate\Support\Str::limit($project->title, 55) }}
                        </a>
                    </h3>
                    <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">{{ $project->user->full_name ?? $project->user->name }}</p>
                </div>
                <form method="POST" action="{{ route('saved.toggle', $project) }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1.5rem; padding: 0; color: #ef4444; transition: transform 0.2s;" title="Remove from saved">❤️</button>
                </form>
            </div>

            {{-- Description --}}
            <p style="margin: 0 0 1rem 0; color: #4b5563; font-size: 0.875rem; line-height: 1.6; flex: 1;">
                {{ \Illuminate\Support\Str::limit($project->description ?? $project->abstract ?? 'No description', 120) }}
            </p>

            {{-- Status badge and category --}}
            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                @php
                    $bgColor = $colors['bg'];
                    $textColor = $colors['text'];
                @endphp
                <span class="status-badge" data-bg="{{ $bgColor }}" data-text="{{ $textColor }}">
                    {{ str_replace('_', ' ', $project->status) }}
                </span>
                <span style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem;">
                    {{ $project->category ?? 'Uncategorized' }}
                </span>
            </div>

            {{-- Stats --}}
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; font-size: 0.875rem; color: #6b7280; border-top: 1px solid #f3f4f6; padding-top: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.35rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>{{ number_format($project->views_count ?? 0) }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.35rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <span>{{ number_format($project->downloads_count ?? 0) }}</span>
                </div>
            </div>

            {{-- Action buttons --}}
            <div style="display: flex; gap: 0.75rem; margin-top: auto;">
                <a href="{{ route('research.show', $project) }}" style="flex: 1; background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-weight: 500; text-align: center; font-size: 0.875rem; transition: background 0.2s;">
                    View Project
                </a>
                @if($project->file_path)
                <a href="{{ route('research.download', $project) }}" style="flex: 1; background: #f3f4f6; color: #374151; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-weight: 500; text-align: center; font-size: 0.875rem; transition: background 0.2s;">
                    Download
                </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($projects->hasPages())
<div style="margin-top: 2.5rem; display: flex; justify-content: center;">
    {{ $projects->links() }}
</div>
@endif
@endif

<style>
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: capitalize;
    }
    @media (hover: hover) {
        div[style*="border: 1px solid #e5e7eb"] {
            cursor: pointer;
        }
        div[style*="border: 1px solid #e5e7eb"]:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        a[style*="background: #2563eb"] {
            cursor: pointer;
        }
        a[style*="background: #2563eb"]:hover {
            background: #1d4ed8;
        }
        a[style*="background: #f3f4f6"] {
            cursor: pointer;
        }
        a[style*="background: #f3f4f6"]:hover {
            background: #e5e7eb;
        }
    }
</style>

<script>
    document.querySelectorAll('.status-badge').forEach(el => {
        el.style.backgroundColor = el.getAttribute('data-bg');
        el.style.color = el.getAttribute('data-text');
    });
</script>
@endsection
