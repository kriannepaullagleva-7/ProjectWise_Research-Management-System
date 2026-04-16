@extends('layouts.app')
@section('title', $researchProject->title)
@section('page-title', 'Research Details')
 
@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">
    {{-- Main Content --}}
    <div>
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-body">
                <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1rem;">
                    <span class="badge badge-{{ ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected','under_review'=>'review','revision_needed'=>'revision','draft'=>'draft'][$researchProject->status] ?? 'draft' }}">{{ $researchProject->status_label }}</span>
                    <span style="font-size:.75rem;color:var(--ink-mute);background:var(--surface);padding:.15rem .6rem;border-radius:99px;border:1px solid var(--border);">{{ $researchProject->category }}</span>
                    @if($researchProject->field_of_study)
                    <span style="font-size:.75rem;color:var(--ink-mute);background:var(--surface);padding:.15rem .6rem;border-radius:99px;border:1px solid var(--border);">{{ $researchProject->field_of_study }}</span>
                    @endif
                </div>
                <h1 style="font-size:1.5rem;margin-bottom:1rem;line-height:1.3;">{{ $researchProject->title }}</h1>
 
                <div style="display:flex;align-items:center;gap:1.25rem;padding:1rem 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin-bottom:1.25rem;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <img src="{{ $researchProject->user->avatar_url }}" style="width:32px;height:32px;border-radius:50%;" alt="">
                        <div>
                            <div style="font-size:.8125rem;font-weight:500;">{{ $researchProject->user->full_name }}</div>
                            <div style="font-size:.7rem;color:var(--ink-mute);">{{ $researchProject->user->department ?? 'Researcher' }}</div>
                        </div>
                    </div>
                    <div style="font-size:.8rem;color:var(--ink-mute);">
                        📅 Submitted {{ $researchProject->submission_date?->format('F d, Y') ?? $researchProject->created_at->format('F d, Y') }}
                    </div>
                    @if($researchProject->approval_date)
                    <div style="font-size:.8rem;color:#059669;">✓ Approved {{ $researchProject->approval_date->format('F d, Y') }}</div>
                    @endif
                    <div style="font-size:.8rem;color:var(--ink-mute);">👁 {{ number_format($researchProject->views_count) }} views</div>
                    <div style="font-size:.8rem;color:var(--ink-mute);">⬇ {{ number_format($researchProject->downloads_count) }} downloads</div>
                </div>
 
                <h3 style="font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--ink-mute);margin-bottom:.75rem;">Abstract</h3>
                <p style="line-height:1.8;color:var(--ink-soft);font-size:.9375rem;">{{ $researchProject->abstract }}</p>
 
                @if($researchProject->keywords)
                <div style="margin-top:1.25rem;">
                    <span style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--ink-mute);">Keywords: </span>
                    @foreach(explode(',', $researchProject->keywords) as $kw)
                    <span style="display:inline-block;background:var(--accent-tint);color:var(--accent);font-size:.75rem;padding:.15rem .6rem;border-radius:99px;margin:.2rem .1rem;">{{ trim($kw) }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
 
        {{-- Faculty Feedback --}}
        @if($researchProject->feedbacks->isNotEmpty())
        <div class="card">
            <div class="card-header"><h3>Faculty Feedback</h3></div>
            @foreach($researchProject->feedbacks as $fb)
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #f9fafb;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.65rem;flex-wrap:wrap;gap:.5rem;">
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <img src="{{ $fb->faculty->avatar_url }}" style="width:28px;height:28px;border-radius:50%;" alt="">
                        <span style="font-size:.8125rem;font-weight:500;">{{ $fb->faculty->full_name }}</span>
                        <span style="font-size:.72rem;color:var(--ink-mute);">· Faculty</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:.65rem;">
                        @if($fb->decision)
                        <span class="badge {{ $fb->decision === 'approved' ? 'badge-approved' : ($fb->decision === 'rejected' ? 'badge-rejected' : 'badge-revision') }}">
                            {{ ucfirst(str_replace('_', ' ', $fb->decision)) }}
                        </span>
                        @endif
                        @if($fb->rating)
                        <span style="font-size:.8rem;color:#d97706;">{{ str_repeat('★', $fb->rating) }}{{ str_repeat('☆', 5 - $fb->rating) }}</span>
                        @endif
                        <span style="font-size:.72rem;color:var(--ink-mute);">{{ $fb->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <p style="font-size:.875rem;color:var(--ink-soft);line-height:1.7;">{{ $fb->comment }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
 
    {{-- Sidebar Actions --}}
    <div style="position:sticky;top:5rem;">
        <div class="card" style="margin-bottom:1rem;">
            <div class="card-body">
                @if($researchProject->file_path)
                <a href="{{ route('research.download', $researchProject) }}" class="btn btn-primary" style="width:100%;margin-bottom:.65rem;justify-content:center;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download Paper
                </a>
                <div style="font-size:.72rem;text-align:center;color:var(--ink-mute);margin-bottom:.875rem;">{{ $researchProject->file_original_name }} · {{ $researchProject->file_size_formatted }}</div>
                @endif
 
                {{-- Save toggle --}}
                @auth
                <form method="POST" action="{{ route('saved.toggle', $researchProject) }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center;">
                        @if($isSaved)
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                        Saved to Library
                        @else
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                        Save to Library
                        @endif
                    </button>
                </form>
                @endauth
 
                @if(auth()->id() === $researchProject->user_id && in_array($researchProject->status, ['draft','revision_needed']))
                <a href="{{ route('research.edit', $researchProject) }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:.5rem;">Edit & Resubmit</a>
                @endif
 
                @if(in_array(auth()->user()->role, ['faculty','admin']))
                <a href="{{ route('faculty.review', $researchProject) }}" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:.5rem;">Review This Project</a>
                @endif
            </div>
        </div>
 
        {{-- Meta Card --}}
        <div class="card">
            <div class="card-body" style="font-size:.8125rem;">
                @foreach([
                    ['Category', $researchProject->category],
                    ['Field', $researchProject->field_of_study],
                    ['Assigned to', $researchProject->assignedFaculty?->full_name],
                ] as [$label, $val])
                @if($val)
                <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f3f4f6;">
                    <span style="color:var(--ink-mute);">{{ $label }}</span>
                    <span style="font-weight:500;color:var(--ink-soft);">{{ $val }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection