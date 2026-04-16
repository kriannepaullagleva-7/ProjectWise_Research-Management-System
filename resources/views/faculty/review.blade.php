@extends('layouts.app')
@section('title', 'Review Project - ' . $project->title)

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <!-- Back Button -->
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('faculty.explorer') }}" style="color: #2563eb; text-decoration: none; font-size: 0.875rem;">← Back to Queue</a>
    </div>

    <!-- Project Information -->
    <div style="background: white; border-radius: 0.5rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <h1 style="margin: 0 0 1rem 0; font-size: 1.875rem; font-weight: 700;">{{ $project->title }}</h1>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e5e7eb;">
            <div>
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Student</div>
                <div style="font-weight: 600;">{{ $project->user->full_name ?? $project->user->name }}</div>
                <div style="color: #6b7280; font-size: 0.75rem;">{{ $project->user->email }}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Category</div>
                <div style="font-weight: 600;">{{ $project->category }}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Status</div>
                @php
                    $statusColors = [
                        'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'under_review' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                        'approved' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                        'rejected' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'],
                    ];
                    $colors = $statusColors[$project->status] ?? ['bg' => '#e5e7eb', 'text' => '#374151'];
                @endphp
                <span style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; display: inline-block;">
                    {{ $project->status_label }}
                </span>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Submitted</div>
                <div style="font-weight: 600;">{{ $project->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        <!-- Project Description -->
        <div style="margin-bottom: 2rem;">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600;">Project Description</h3>
            <div style="color: #374151; line-height: 1.6; white-space: pre-wrap;">{{ $project->description }}</div>
        </div>

        <!-- File Download -->
        @if($project->file_path)
        <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('research.download', $project) }}" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.5rem;">
                📥 Download File
            </a>
        </div>
        @endif

        <!-- Previous Reviews -->
        @if($project->reviews->count() > 0)
        <div style="margin-bottom: 2rem; padding: 1rem; background: #f0fdf4; border-left: 4px solid #22c55e; border-radius: 0.375rem;">
            <h4 style="margin: 0 0 1rem 0; color: #15803d;">Previous Reviews</h4>
            @foreach($project->reviews as $prevReview)
            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #dcfce7;">
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">
                    <strong>{{ $prevReview->faculty->full_name ?? $prevReview->faculty->name }}</strong> - 
                    {{ $prevReview->created_at->format('M d, Y H:i') }}
                </div>
                <div style="color: #374151; margin-bottom: 0.5rem;">{{ $prevReview->feedback }}</div>
                @if($prevReview->recommendation)
                <div style="font-size: 0.875rem;">
                    <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                        {{ ucfirst($prevReview->recommendation) }}
                    </span>
                    @if($prevReview->rating)
                    <span style="margin-left: 0.5rem;">Rating: {{ $prevReview->rating }}/5 ⭐</span>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Review Form -->
    <div style="background: white; border-radius: 0.5rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 600;">Submit Your Review</h2>

        @if ($errors->any())
        <div style="background: #fee2e2; color: #7f1d1d; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('faculty.feedback', $project) }}" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @csrf

            <!-- Feedback Textarea -->
            <div>
                <label for="feedback" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #111827;">
                    Review Feedback <span style="color: #ef4444;">*</span>
                </label>
                <textarea 
                    id="feedback" 
                    name="feedback" 
                    required
                    rows="8"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: inherit; resize: vertical;"
                    placeholder="Please provide detailed feedback about this project...">{{ old('feedback', $review->feedback ?? '') }}</textarea>
                <div style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem;">
                    Minimum 10 characters required
                </div>
            </div>

            <!-- Recommendation Select -->
            <div>
                <label for="recommendation" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #111827;">
                    Recommendation <span style="color: #ef4444;">*</span>
                </label>
                <select 
                    id="recommendation" 
                    name="recommendation" 
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: inherit;">
                    <option value="">-- Select a recommendation --</option>
                    <option value="approve" {{ old('recommendation', $review->recommendation ?? '') === 'approve' ? 'selected' : '' }}>
                        ✓ Approve - Project is ready for publication
                    </option>
                    <option value="revise" {{ old('recommendation', $review->recommendation ?? '') === 'revise' ? 'selected' : '' }}>
                        ◐ Revise - Project needs improvements
                    </option>
                    <option value="reject" {{ old('recommendation', $review->recommendation ?? '') === 'reject' ? 'selected' : '' }}>
                        ✕ Reject - Project does not meet standards
                    </option>
                </select>
            </div>

            <!-- Rating -->
            <div>
                <label for="rating" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #111827;">
                    Project Rating
                </label>
                <div style="display: flex; gap: 1rem;">
                    @for ($i = 1; $i <= 5; $i++)
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input 
                            type="radio" 
                            name="rating" 
                            value="{{ $i }}"
                            {{ old('rating', $review->rating ?? '') == $i ? 'checked' : '' }}
                            style="cursor: pointer;">
                        <span style="font-size: 1.25rem;">{{ str_repeat('⭐', $i) }}</span>
                    </label>
                    @endfor
                </div>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <button 
                    type="submit" 
                    style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.background='#1d4ed8'"
                    onmouseout="this.style.background='#2563eb'">
                    Submit Review
                </button>
                <a 
                    href="{{ route('faculty.explorer') }}" 
                    style="background: #e5e7eb; color: #374151; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; text-decoration: none; display: inline-block; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.background='#d1d5db'"
                    onmouseout="this.style.background='#e5e7eb'">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection