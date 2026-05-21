@extends('layouts.app')
@section('title', 'Review — ' . $project->title)
@section('page-title', 'Review Submission')

@push('styles')
<style>
    .rev-grid { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }
    @media (max-width: 900px) { .rev-grid { grid-template-columns:1fr; } }

    .rev-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: var(--surface);
        border-bottom: 1px solid var(--border);
    }
    .rev-meta-item { display:flex; flex-direction:column; gap:.2rem; }
    .rev-meta-label { font-size:.7rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase; color:var(--ink-mute); }
    .rev-meta-val   { font-size:.875rem; font-weight:500; color:var(--ink); }

    .star-group { display:flex; gap:.5rem; }
    .star-label { display:flex; align-items:center; gap:.35rem; cursor:pointer; }
    .star-label input { display:none; }
    .star-icon { font-size:1.35rem; color:#d1d5db; transition:color .1s; }
    .star-label:hover .star-icon,
    .star-label:has(input:checked) ~ .star-label .star-icon { color:#d1d5db; }
    .star-label input:checked ~ .star-icon,
    .star-label:hover .star-icon { color:#f59e0b; }

    .prev-review {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .prev-review:last-child { border-bottom: none; }
</style>
@endpush

@section('content')
<div style="margin-bottom:1.25rem;">
    <a href="{{ route('faculty.explorer') }}" class="btn btn-ghost btn-sm">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Queue
    </a>
</div>

<div class="rev-grid">

    {{-- LEFT: Project Info + Form --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Project info card --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 style="font-size:1rem;font-weight:700;color:var(--ink);margin:0 0 .25rem;">{{ $project->title }}</h3>
                    <div style="font-size:.8125rem;color:var(--ink-mute);">Submitted by {{ $project->user->full_name ?? $project->user->name }}</div>
                </div>
                <span class="badge badge-{{ ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected','under_review'=>'review'][$project->status] ?? 'draft' }}">
                    {{ $project->status_label }}
                </span>
            </div>

            <div class="rev-meta-grid">
                <div class="rev-meta-item">
                    <span class="rev-meta-label">Student</span>
                    <span class="rev-meta-val">{{ $project->user->full_name ?? $project->user->name }}</span>
                    <span style="font-size:.75rem;color:var(--ink-mute);">{{ $project->user->email }}</span>
                </div>
                <div class="rev-meta-item">
                    <span class="rev-meta-label">Category</span>
                    <span class="rev-meta-val">{{ $project->category }}</span>
                </div>
                <div class="rev-meta-item">
                    <span class="rev-meta-label">Submitted</span>
                    <span class="rev-meta-val">{{ $project->created_at->format('M d, Y') }}</span>
                    <span style="font-size:.75rem;color:var(--ink-mute);">{{ $project->created_at->diffForHumans() }}</span>
                </div>
            </div>

            @if($project->abstract || $project->description)
            <div class="card-body">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ink-mute);margin-bottom:.65rem;">Abstract</div>
                <p style="font-size:.875rem;color:var(--ink-soft);line-height:1.75;margin:0;white-space:pre-wrap;">{{ $project->description ?? $project->abstract }}</p>
            </div>
            @endif
        </div>

        {{-- Previous reviews --}}
        @if($project->reviews->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3>Previous Reviews</h3>
                <span style="font-size:.75rem;color:var(--ink-mute);">{{ $project->reviews->count() }} review(s)</span>
            </div>
            <div>
                @foreach($project->reviews as $prev)
                <div class="prev-review">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;flex-wrap:wrap;gap:.5rem;">
                        <div style="font-size:.8125rem;font-weight:600;color:var(--ink);">
                            {{ $prev->faculty->full_name ?? $prev->faculty->name }}
                        </div>
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            @if($prev->recommendation)
                            <span class="badge badge-{{ $prev->recommendation === 'approve' ? 'approved' : ($prev->recommendation === 'reject' ? 'rejected' : 'review') }}">
                                {{ ucfirst($prev->recommendation) }}
                            </span>
                            @endif
                            @if($prev->rating)
                            <span style="color:#f59e0b;font-size:.8rem;">{{ str_repeat('★',$prev->rating) }}{{ str_repeat('☆',5-$prev->rating) }}</span>
                            @endif
                            <span style="font-size:.72rem;color:var(--ink-mute);">{{ $prev->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <p style="font-size:.8125rem;color:var(--ink-soft);line-height:1.65;margin:0;">{{ $prev->feedback }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Review Form --}}
        <div class="card">
            <div class="card-header"><h3>Submit Your Review</h3></div>
            <div class="card-body">

                @if($errors->any())
                <div class="flash flash-error" style="margin-bottom:1.25rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('faculty.feedback', $project) }}" method="POST" style="display:flex;flex-direction:column;gap:1.25rem;">
                    @csrf

                    <div>
                        <label class="form-label" for="feedback">
                            Review Feedback <span style="color:var(--danger);">*</span>
                        </label>
                        <textarea class="form-input" id="feedback" name="feedback" required rows="7"
                                  placeholder="Provide detailed, constructive feedback about this research project…">{{ old('feedback', $review->feedback ?? '') }}</textarea>
                        <div style="font-size:.75rem;color:var(--ink-mute);margin-top:.3rem;">Minimum 10 characters required.</div>
                        @error('feedback')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="recommendation">
                            Recommendation <span style="color:var(--danger);">*</span>
                        </label>
                        <select class="form-input" id="recommendation" name="recommendation" required>
                            <option value="">— Select a recommendation —</option>
                            <option value="approve" {{ old('recommendation', $review->recommendation ?? '') === 'approve' ? 'selected' : '' }}>Approve — Ready for publication</option>
                            <option value="revise"  {{ old('recommendation', $review->recommendation ?? '') === 'revise'  ? 'selected' : '' }}>Revise — Needs improvements</option>
                            <option value="reject"  {{ old('recommendation', $review->recommendation ?? '') === 'reject'  ? 'selected' : '' }}>Reject — Does not meet standards</option>
                        </select>
                        @error('recommendation')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Project Rating <span style="font-size:.75rem;color:var(--ink-mute);">(optional)</span></label>
                        <div class="star-group" id="starGroup">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="star-label">
                                <input type="radio" name="rating" value="{{ $i }}"
                                       {{ old('rating', $review->rating ?? '') == $i ? 'checked' : '' }}>
                                <span class="star-icon">★</span>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <div style="display:flex;gap:.75rem;padding-top:1rem;border-top:1px solid var(--border);">
                        <button type="submit" class="btn btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            Submit Review
                        </button>
                        <a href="{{ route('faculty.explorer') }}" class="btn btn-ghost">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Sidebar --}}
    <div style="position:sticky;top:5rem;display:flex;flex-direction:column;gap:1rem;">

        {{-- File download --}}
        @if($project->file_path)
        <div class="card">
            <div class="card-header"><h3>Document</h3></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:.65rem;">
                <div style="display:flex;align-items:center;gap:.75rem;background:var(--surface);border:1px solid var(--border);border-radius:9px;padding:.875rem;">
                    <div style="width:38px;height:38px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.75"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:.8125rem;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $project->file_original_name ?: basename($project->file_path) }}
                        </div>
                        <div style="font-size:.72rem;color:var(--ink-mute);margin-top:.1rem;">Research document</div>
                    </div>
                </div>
                <a href="{{ route('research.download', $project) }}" class="btn btn-primary" style="justify-content:center;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download File
                </a>
            </div>
        </div>
        @endif

        {{-- Project details --}}
        <div class="card">
            <div class="card-header"><h3>Project Details</h3></div>
            <div style="padding:0;">
                @foreach([
                    ['Category',    $project->category],
                    ['Field',       $project->field_of_study],
                    ['Views',       number_format($project->views_count)],
                    ['Downloads',   number_format($project->downloads_count)],
                ] as [$lbl, $val])
                @if($val)
                <div style="display:flex;justify-content:space-between;padding:.75rem 1.5rem;border-bottom:1px solid #f3f4f6;font-size:.8125rem;">
                    <span style="color:var(--ink-mute);">{{ $lbl }}</span>
                    <span style="font-weight:500;color:var(--ink-soft);">{{ $val }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- View full project --}}
        <a href="{{ route('research.show', $project) }}" class="btn btn-ghost" style="justify-content:center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            View Full Project
        </a>
    </div>

</div>

@push('scripts')
<script>
// Highlight stars up to the selected one
document.querySelectorAll('#starGroup .star-label').forEach((label, idx, labels) => {
    label.addEventListener('mouseenter', () => {
        labels.forEach((l, i) => {
            l.querySelector('.star-icon').style.color = i <= idx ? '#f59e0b' : '#d1d5db';
        });
    });
    label.addEventListener('mouseleave', () => {
        const checked = document.querySelector('#starGroup input:checked');
        const checkedIdx = checked ? parseInt(checked.value) - 1 : -1;
        labels.forEach((l, i) => {
            l.querySelector('.star-icon').style.color = i <= checkedIdx ? '#f59e0b' : '#d1d5db';
        });
    });
    label.querySelector('input').addEventListener('change', () => {
        labels.forEach((l, i) => {
            l.querySelector('.star-icon').style.color = i <= idx ? '#f59e0b' : '#d1d5db';
        });
    });
});
// Init on page load
const checkedStar = document.querySelector('#starGroup input:checked');
if (checkedStar) {
    const checkedIdx = parseInt(checkedStar.value) - 1;
    document.querySelectorAll('#starGroup .star-icon').forEach((icon, i) => {
        icon.style.color = i <= checkedIdx ? '#f59e0b' : '#d1d5db';
    });
}
</script>
@endpush

@endsection
