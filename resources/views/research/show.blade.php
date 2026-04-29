@extends('layouts.app')
@section('title', $researchProject->title)
@section('page-title', 'Research Details')

@push('styles')
<style>
    .research-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.5rem;
        align-items: start;
    }
    .meta-row {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border);
        border-top: 1px solid var(--border);
        flex-wrap: wrap;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: .4rem;
        font-size: .8rem;
        color: var(--ink-mute);
    }
    .keyword-tag {
        display: inline-block;
        background: var(--accent-tint);
        color: var(--accent);
        font-size: .72rem;
        padding: .22rem .65rem;
        border-radius: 99px;
        margin: .15rem .1rem;
        font-weight: 500;
    }
    .review-item {
        padding: 1.25rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .review-item:last-child { border-bottom: none; }
    .review-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: .75rem;
        flex-wrap: wrap;
        gap: .5rem;
    }
    .avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        background: #e5e7eb;
    }
    .sidebar-sticky { position: sticky; top: 5rem; }

    /* PDF Viewer */
    .pdf-viewer-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.65);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .pdf-viewer-overlay.open { display: flex; }
    .pdf-viewer-box {
        background: #1a1a2e;
        border-radius: 16px;
        overflow: hidden;
        width: 100%;
        max-width: 900px;
        height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 60px rgba(0,0,0,.5);
    }
    .pdf-viewer-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .875rem 1.25rem;
        background: #0f0f23;
        flex-shrink: 0;
    }
    .pdf-viewer-bar span { color: #e2e8f0; font-size: .875rem; font-weight: 500; }
    .pdf-viewer-close {
        background: rgba(255,255,255,.1);
        border: none;
        color: #e2e8f0;
        padding: .4rem .85rem;
        border-radius: 7px;
        cursor: pointer;
        font-size: .8125rem;
        font-weight: 600;
        transition: background .15s;
    }
    .pdf-viewer-close:hover { background: rgba(255,255,255,.2); }
    .pdf-viewer-frame {
        flex: 1;
        border: none;
        background: #525659;
        width: 100%;
    }

    @media (max-width: 1024px) {
        .research-grid { grid-template-columns: 1fr 260px; }
    }
    @media (max-width: 768px) {
        .research-grid { grid-template-columns: 1fr; }
        .sidebar-sticky { position: relative; top: 0; }
        .meta-row { flex-direction: column; align-items: flex-start; gap: .5rem; }
    }
</style>
@endpush

@section('content')
<div class="research-grid">

    {{-- Main Content --}}
    <div>
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-body">
                {{-- Status & Category --}}
                <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1rem;">
                    <span class="badge badge-{{ ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected','under_review'=>'review'][$researchProject->status] ?? 'draft' }}">
                        {{ $researchProject->status_label }}
                    </span>
                    <span style="font-size:.72rem;color:var(--ink-mute);background:var(--surface);padding:.2rem .65rem;border-radius:99px;border:1px solid var(--border);">
                        {{ $researchProject->category }}
                    </span>
                    @if($researchProject->field_of_study)
                    <span style="font-size:.72rem;color:var(--ink-mute);background:var(--surface);padding:.2rem .65rem;border-radius:99px;border:1px solid var(--border);">
                        {{ $researchProject->field_of_study }}
                    </span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 style="font-size:1.625rem;margin:0 0 1.25rem;line-height:1.3;color:var(--ink);">
                    {{ $researchProject->title }}
                </h1>

                {{-- Author & Meta --}}
                <div class="meta-row" style="margin:1rem 0;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        @if($researchProject->user->avatar_url)
                            <img src="{{ $researchProject->user->avatar_url }}" class="avatar" alt="">
                        @else
                            <div class="avatar" style="background:#2d5be3;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem;">
                                {{ strtoupper(substr($researchProject->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight:600;color:var(--ink);font-size:.9rem;">{{ $researchProject->user->full_name ?? $researchProject->user->name }}</div>
                            <div style="font-size:.72rem;color:var(--ink-mute);">{{ $researchProject->user->department ?? 'Researcher' }}</div>
                        </div>
                    </div>

                    <div class="meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $researchProject->submission_date?->format('M d, Y') ?? $researchProject->created_at->format('M d, Y') }}
                    </div>

                    @if($researchProject->approval_date)
                    <div class="meta-item" style="color:#059669;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Approved {{ $researchProject->approval_date->format('M d, Y') }}
                    </div>
                    @endif

                    <div class="meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        {{ number_format($researchProject->views_count) }} views
                    </div>

                    <div class="meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        {{ number_format($researchProject->downloads_count) }} downloads
                    </div>
                </div>

                {{-- Abstract --}}
                <div style="margin-top:1.5rem;">
                    <h3 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ink-mute);margin-bottom:.65rem;">Abstract</h3>
                    <p style="line-height:1.85;color:var(--ink-soft);font-size:.9375rem;margin:0;white-space:pre-wrap;">{{ $researchProject->abstract }}</p>
                </div>

                {{-- Keywords --}}
                @if($researchProject->keywords)
                <div style="margin-top:1.5rem;">
                    <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ink-mute);display:block;margin-bottom:.5rem;">Keywords</span>
                    <div>
                        @foreach(explode(',', $researchProject->keywords) as $kw)
                        @if(trim($kw))
                        <span class="keyword-tag">{{ trim($kw) }}</span>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Document Preview Panel --}}
        @if($researchProject->file_path)
        @php
            $ext = strtolower(pathinfo($researchProject->file_path, PATHINFO_EXTENSION));
            $isPdf = $ext === 'pdf';
            $fileUrl = asset('storage/' . $researchProject->file_path);
        @endphp
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-header">
                <h3>Document</h3>
                <div style="display:flex;gap:.5rem;">
                    @if($isPdf)
                    <button onclick="openPdfViewer()" class="btn btn-ghost btn-sm">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Preview
                    </button>
                    @endif
                    <a href="{{ route('research.download', $researchProject) }}" class="btn btn-primary btn-sm">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download
                    </a>
                </div>
            </div>
            <div class="card-body" style="padding:1.25rem;">
                <div style="display:flex;align-items:center;gap:.875rem;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1rem;">
                    <div style="width:44px;height:44px;border-radius:10px;background:{{ $isPdf ? '#fee2e2' : '#dbeafe' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="{{ $isPdf ? '#dc2626' : '#2563eb' }}" stroke-width="1.75">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:.875rem;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $researchProject->file_original_name ?: basename($researchProject->file_path) }}
                        </div>
                        <div style="font-size:.75rem;color:var(--ink-mute);margin-top:.15rem;">
                            {{ strtoupper($ext) }} file
                            @if($researchProject->file_size_formatted)
                            · {{ $researchProject->file_size_formatted }}
                            @endif
                        </div>
                    </div>
                    @if($isPdf)
                    <button onclick="openPdfViewer()" style="background:none;border:none;cursor:pointer;color:var(--accent);font-size:.8rem;font-weight:600;white-space:nowrap;padding:.35rem .75rem;border:1px solid var(--accent-tint);border-radius:7px;background:var(--accent-tint);">
                        Open Preview
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Faculty Feedback --}}
        @if($researchProject->reviews && $researchProject->reviews->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3>Faculty Feedback</h3>
                <span style="font-size:.75rem;color:var(--ink-mute);">{{ $researchProject->reviews->count() }} review(s)</span>
            </div>
            <div class="card-body" style="padding:0;">
                @foreach($researchProject->reviews as $review)
                <div class="review-item" style="padding:1.25rem 1.5rem;">
                    <div class="review-header">
                        <div style="display:flex;align-items:center;gap:.65rem;">
                            @if($review->faculty->avatar_url)
                            <img src="{{ $review->faculty->avatar_url }}" class="avatar" alt="">
                            @else
                            <div class="avatar" style="background:#7c3aed;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.7rem;">
                                {{ strtoupper(substr($review->faculty->name,0,1)) }}
                            </div>
                            @endif
                            <div>
                                <div style="font-weight:600;font-size:.85rem;color:var(--ink);">{{ $review->faculty->full_name ?? $review->faculty->name }}</div>
                                <div style="font-size:.7rem;color:var(--ink-mute);">Faculty Reviewer</div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:.65rem;flex-wrap:wrap;">
                            @if($review->recommendation)
                            <span class="badge badge-{{ $review->recommendation === 'approve' ? 'approved' : ($review->recommendation === 'reject' ? 'rejected' : 'review') }}">
                                {{ ucfirst($review->recommendation) }}
                            </span>
                            @endif
                            @if($review->rating)
                            <span style="font-size:.8rem;color:#d97706;font-weight:600;">
                                {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                            </span>
                            @endif
                            <span style="font-size:.7rem;color:var(--ink-mute);">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <p style="font-size:.875rem;color:var(--ink-soft);line-height:1.75;margin:.65rem 0 0;">{{ $review->feedback }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="card" style="text-align:center;padding:2rem;">
            <p style="color:var(--ink-mute);margin:0;font-size:.875rem;">No faculty feedback yet.</p>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="sidebar-sticky">
        <div class="card" style="margin-bottom:1rem;">
            <div class="card-body" style="display:flex;flex-direction:column;gap:.6rem;">

                @if($researchProject->file_path)
                <a href="{{ route('research.download', $researchProject) }}" class="btn btn-primary" style="width:100%;justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download Paper
                </a>
                @endif

                @auth
                <form method="POST" action="{{ route('saved.toggle', $researchProject) }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center;">
                        @if($isSaved)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                        Saved to Library
                        @else
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                        Save to Library
                        @endif
                    </button>
                </form>
                @endauth

                @if(auth()->id() === $researchProject->user_id && in_array($researchProject->status, ['pending','under_review','rejected']))
                <a href="{{ route('research.edit', $researchProject) }}" class="btn btn-ghost" style="width:100%;justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit &amp; Resubmit
                </a>
                @endif

                @if(auth()->check() && in_array(auth()->user()->role, ['faculty','admin']))
                <a href="{{ route('faculty.review', $researchProject) }}" class="btn btn-primary" style="width:100%;justify-content:center;background:#7c3aed;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Write Review
                </a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>Details</h3></div>
            <div class="card-body" style="padding:0;">
                @foreach([
                    ['Category',    $researchProject->category],
                    ['Field',       $researchProject->field_of_study],
                    ['Assigned to', $researchProject->assignedFaculty?->full_name ?? $researchProject->assignedFaculty?->name],
                ] as [$label, $val])
                @if($val)
                <div style="display:flex;justify-content:space-between;padding:.875rem 1.5rem;border-bottom:1px solid #f3f4f6;font-size:.8125rem;">
                    <span style="color:var(--ink-mute);">{{ $label }}</span>
                    <span style="font-weight:500;color:var(--ink-soft);">{{ $val }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- PDF Viewer Modal --}}
@if(isset($isPdf) && $isPdf)
<div class="pdf-viewer-overlay" id="pdfViewerOverlay">
    <div class="pdf-viewer-box">
        <div class="pdf-viewer-bar">
            <span>{{ $researchProject->file_original_name ?: basename($researchProject->file_path) }}</span>
            <div style="display:flex;gap:.5rem;align-items:center;">
                <a href="{{ route('research.download', $researchProject) }}"
                   style="background:rgba(255,255,255,.1);border:none;color:#e2e8f0;padding:.4rem .85rem;border-radius:7px;font-size:.8125rem;font-weight:600;text-decoration:none;">
                    Download
                </a>
                <button onclick="closePdfViewer()" class="pdf-viewer-close">✕ Close</button>
            </div>
        </div>
        <iframe id="pdfFrame" class="pdf-viewer-frame" src="" title="Document Preview"></iframe>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function openPdfViewer() {
    const overlay = document.getElementById('pdfViewerOverlay');
    const frame   = document.getElementById('pdfFrame');
    if (!overlay || !frame) return;
    frame.src = '{{ isset($fileUrl) ? $fileUrl : '' }}';
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closePdfViewer() {
    const overlay = document.getElementById('pdfViewerOverlay');
    const frame   = document.getElementById('pdfFrame');
    if (!overlay || !frame) return;
    overlay.classList.remove('open');
    frame.src = '';
    document.body.style.overflow = '';
}
document.getElementById('pdfViewerOverlay')?.addEventListener('click', function(e) {
    if (e.target === this) closePdfViewer();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePdfViewer();
});
</script>
@endpush
