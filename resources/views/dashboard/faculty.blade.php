@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')
@section('title', 'Faculty Dashboard')
@section('page-title', 'Faculty Dashboard')

@section('content')
<!-- Statistics Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:1rem;margin-bottom:2rem;">
    <!-- Pending Reviews Card -->
    <div class="stat-card" style="background:linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Pending Reviews</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['pending_reviews'] }}</div>
        <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.5rem;">Awaiting assignment</div>
    </div>

    <!-- Under Review Card -->
    <div class="stat-card" style="background:linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Under Review</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['under_review'] }}</div>
        <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.5rem;">In progress reviews</div>
    </div>

    <!-- My Reviews Done Card -->
    <div class="stat-card" style="background:linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Reviews Completed</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['reviewed_total'] }}</div>
        <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.5rem;">Total completed</div>
    </div>

    <!-- Total Approved Card -->
    <div class="stat-card" style="background:linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Projects Approved</div>
        <div style="font-size: 2rem; font-weight: 700;">{{ $stats['approved'] }}</div>
        <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.5rem;">Successfully approved</div>
    </div>
</div>

<!-- Submissions Awaiting Review Section -->
<div style="background: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Submissions Awaiting Review</h3>
        <a href="{{ route('faculty.explorer') }}" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: background 0.2s;">Review Queue</a>
    </div>

    @if($assignedProjects->isEmpty())
        <div style="padding:3rem;text-align:center;color:#6b7280;font-size:.875rem;">
            <svg style="width: 3rem; height: 3rem; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p>No pending submissions at this time. Great work! 🎉</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Title</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Student</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Category</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Submitted</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.875rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedProjects as $p)
                    <tr style="border-bottom: 1px solid #e5e7eb; transition: background 0.1s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                        <td style="padding: 1rem; font-weight: 500; color: #111827;">
                            <a href="{{ route('research.show', $p) }}" style="color: #2563eb; text-decoration: none;">
                                {{ Str::limit($p->title, 45) }}
                            </a>
                        </td>
                        <td style="padding: 1rem; font-size: 0.8125rem; color: #6b7280;">
                            {{ $p->user->full_name ?? $p->user->name }}
                        </td>
                        <td style="padding: 1rem; font-size: 0.8125rem; color: #6b7280;">
                            <span style="background: #e0e7ff; color: #4f46e5; padding: 0.25rem 0.75rem; border-radius: 0.25rem;">
                                {{ $p->category }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            @php
                                $statusColors = [
                                    'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'under_review' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    'approved' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                    'rejected' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'],
                                ];
                                $colors = $statusColors[$p->status] ?? ['bg' => '#e5e7eb', 'text' => '#374151'];
                            @endphp
                            <span data-status-badge="true" data-bg="{{ $colors['bg'] }}" data-text="{{ $colors['text'] }}" style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; display: inline-block;">
                                {{ $p->status_label }}
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.8125rem; color: #6b7280;">
                            {{ $p->created_at->format('M d, Y') }}
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if(in_array($p->status, ['pending','under_review']))
                                <a href="{{ route('faculty.review', $p) }}" style="background: #2563eb; color: white; padding: 0.375rem 0.75rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.75rem; font-weight: 500; display: inline-block; transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">Review</a>
                            @else
                                <a href="{{ route('research.show', $p) }}" style="background: #e5e7eb; color: #374151; padding: 0.375rem 0.75rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.75rem; font-weight: 500; display: inline-block; transition: background 0.2s;" onmouseover="this.style.background='#d1d5db'" onmouseout="this.style.background='#e5e7eb'">View</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Quick Stats Section -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:1rem;">
    <!-- Recent Activity Card -->
    <div style="background: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600;">Quick Stats</h4>
        <div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb;">
                <span style="color: #6b7280;">Total Submissions Assigned</span>
                <span style="font-weight: 600; color: #111827;">{{ $stats['total_submissions'] ?? 0 }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                <span style="color: #6b7280;">Approval Rate</span>
                <span style="font-weight: 600; color: #059669;">
                    @if($stats['reviewed_total'] > 0)
                        {{ round(($stats['approved'] / $stats['reviewed_total']) * 100) }}%
                    @else
                        0%
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Resources Card -->
    <div style="background: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600;">Resources</h4>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 0.5rem 0;">
                <a href="{{ route('faculty.explorer') }}" style="color: #2563eb; text-decoration: none; font-size: 0.875rem;">→ Review Queue</a>
            </li>
            <li style="padding: 0.5rem 0;">
                <a href="{{ route('research.index') }}" style="color: #2563eb; text-decoration: none; font-size: 0.875rem;">→ All Projects</a>
            </li>
            <li style="padding: 0.5rem 0;">
                <a href="{{ route('profile.show') }}" style="color: #2563eb; text-decoration: none; font-size: 0.875rem;">→ My Profile</a>
            </li>
        </ul>
    </div>
</div>

<script>
    // Apply status badge colors from data attributes
    document.querySelectorAll('[data-status-badge]').forEach(badge => {
        badge.style.backgroundColor = badge.dataset.bg;
        badge.style.color = badge.dataset.text;
    });
</script>

@endsection -->