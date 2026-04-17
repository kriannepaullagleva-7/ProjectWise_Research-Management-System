@extends('layouts.app')
@section('title', isset($researchProject) ? 'Edit Research' : 'Submit Research')
@section('page-title', isset($researchProject) ? 'Edit Submission' : 'Submit Research')

@section('content')
<style>
    .submit-wrap {
        max-width: 780px;
    }

    .submit-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #f0f4f8;
        overflow: hidden;
    }

    .submit-card-head {
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid #f0f4f8;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .submit-card-head-icon {
        width: 40px;
        height: 40px;
        background: #eff6ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        flex-shrink: 0;
    }

    .submit-card-head h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .submit-card-head p {
        font-size: .8rem;
        color: #94a3b8;
        margin-top: .1rem;
    }

    .submit-card-body {
        padding: 1.75rem;
    }

    /* ── FORM ELEMENTS ── */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: .8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .45rem;
        letter-spacing: .01em;
    }

    .form-required {
        color: #ef4444;
        margin-left: .15rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: .75rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: .875rem;
        color: #111827;
        background: #fafafa;
        font-family: inherit;
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }

    .form-input::placeholder,
    .form-textarea::placeholder { color: #9ca3af; }

    .form-textarea { resize: vertical; min-height: 160px; line-height: 1.65; }

    .form-hint {
        font-size: .75rem;
        color: #94a3b8;
        margin-top: .4rem;
    }

    .form-error {
        font-size: .75rem;
        color: #dc2626;
        margin-top: .3rem;
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    /* ── CHAR COUNTER ── */
    .char-counter {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: .35rem;
        margin-top: .35rem;
        font-size: .72rem;
        color: #94a3b8;
    }

    /* ── FILE UPLOAD ── */
    .existing-file {
        display: flex;
        align-items: center;
        gap: .75rem;
        background: #f8faff;
        border: 1px solid #dbeafe;
        border-radius: 9px;
        padding: .8rem 1rem;
        margin-bottom: .75rem;
        font-size: .82rem;
    }

    .existing-file-name {
        font-weight: 600;
        color: #1e40af;
    }

    .existing-file-size {
        color: #64748b;
    }

    .drop-zone {
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .15s, background .15s;
        background: #fafbff;
    }

    .drop-zone:hover,
    .drop-zone.drag-over {
        border-color: #93c5fd;
        background: #f0f7ff;
    }

    .drop-zone-icon {
        width: 44px;
        height: 44px;
        background: #eff6ff;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto .875rem;
        color: #3b82f6;
    }

    .drop-zone-label {
        font-size: .875rem;
        color: #64748b;
        margin-bottom: .3rem;
    }

    .drop-zone-label strong {
        color: #2563eb;
        font-weight: 600;
    }

    .drop-zone-formats {
        font-size: .75rem;
        color: #94a3b8;
    }

    /* ── SECTION DIVIDER ── */
    .section-sep {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin: 1.75rem 0;
    }

    .section-sep-line { flex: 1; height: 1px; background: #f0f4f8; }
    .section-sep-text { font-size: .75rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; }

    /* ── ACTIONS ── */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f0f4f8;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        background: #1d4ed8;
        color: white;
        padding: .7rem 1.5rem;
        border-radius: 9px;
        border: none;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: background .15s, transform .1s;
        text-decoration: none;
    }

    .btn-primary:hover { background: #1e40af; transform: translateY(-1px); }
    .btn-primary:active { transform: translateY(0); }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: transparent;
        color: #374151;
        padding: .7rem 1.25rem;
        border-radius: 9px;
        border: 1.5px solid #e5e7eb;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        text-decoration: none;
        transition: background .15s;
    }

    .btn-ghost:hover { background: #f9fafb; color: #374151; }
</style>

<div class="submit-wrap">
    <div class="submit-card">
        <div class="submit-card-head">
            <div class="submit-card-head-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="12" y1="18" x2="12" y2="12"/>
                    <line x1="9" y1="15" x2="15" y2="15"/>
                </svg>
            </div>
            <div>
                <h3>{{ isset($researchProject) ? 'Update Your Research' : 'New Research Submission' }}</h3>
                <p>{{ isset($researchProject) ? 'Revise and resubmit your research project.' : 'Fill out the form below to submit your research for review.' }}</p>
            </div>
        </div>

        <div class="submit-card-body">
            <form method="POST"
                  action="{{ isset($researchProject) ? route('research.update', $researchProject) : route('research.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($researchProject)) @method('PUT') @endif

                {{-- TITLE --}}
                <div class="form-group">
                    <label class="form-label" for="title">
                        Research Title <span class="form-required">*</span>
                    </label>
                    <input class="form-input" type="text" id="title" name="title"
                           value="{{ old('title', $researchProject->title ?? '') }}"
                           placeholder="Enter the full title of your research" required>
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                {{-- CATEGORY + FIELD --}}
                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="category">
                            Category <span class="form-required">*</span>
                        </label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select a category…</option>
                            @foreach(['Computer Science & IT','Engineering','Natural Sciences','Social Sciences','Humanities','Business & Economics','Health & Medicine','Education','Environmental Science','Mathematics','Other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $researchProject->category ?? '') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="field_of_study">Field of Study</label>
                        <input class="form-input" type="text" id="field_of_study" name="field_of_study"
                               value="{{ old('field_of_study', $researchProject->field_of_study ?? '') }}"
                               placeholder="e.g. Machine Learning">
                    </div>
                </div>

                <div class="section-sep">
                    <div class="section-sep-line"></div>
                    <span class="section-sep-text">Details</span>
                    <div class="section-sep-line"></div>
                </div>

                {{-- ABSTRACT --}}
                <div class="form-group">
                    <label class="form-label" for="abstract">
                        Abstract <span class="form-required">*</span>
                    </label>
                    <textarea class="form-textarea" id="abstract" name="abstract"
                              placeholder="Provide a comprehensive summary of your research…" required>{{ old('abstract', $researchProject->abstract ?? '') }}</textarea>
                    <div class="char-counter">
                        <span id="abstract-count">0 characters</span>
                        <span style="color:#e2e8f0;">·</span>
                        <span>min. 100</span>
                    </div>
                    @error('abstract')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                {{-- KEYWORDS --}}
                <div class="form-group">
                    <label class="form-label" for="keywords">Keywords</label>
                    <input class="form-input" type="text" id="keywords" name="keywords"
                           value="{{ old('keywords', $researchProject->keywords ?? '') }}"
                           placeholder="machine learning, neural networks, deep learning">
                    <p class="form-hint">Separate keywords with commas to improve discoverability.</p>
                </div>

                <div class="section-sep">
                    <div class="section-sep-line"></div>
                    <span class="section-sep-text">Document</span>
                    <div class="section-sep-line"></div>
                </div>

                {{-- FILE UPLOAD --}}
                <div class="form-group">
                    <label class="form-label">Research Document</label>

                    @if(isset($researchProject) && $researchProject->file_path)
                        <div class="existing-file">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <span class="existing-file-name">{{ $researchProject->file_original_name }}</span>
                            <span class="existing-file-size">{{ $researchProject->file_size_formatted }}</span>
                            <span style="color:#94a3b8;margin-left:auto;font-size:.75rem;">Upload a new file to replace</span>
                        </div>
                    @endif

                    <div class="drop-zone" id="drop-zone"
                         ondragover="this.classList.add('drag-over');event.preventDefault();"
                         ondragleave="this.classList.remove('drag-over');"
                         ondrop="handleDrop(event)"
                         onclick="document.getElementById('file').click()">
                        <div class="drop-zone-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <p class="drop-zone-label" id="drop-label">
                            Drop your file here or <strong>browse</strong>
                        </p>
                        <p class="drop-zone-formats">PDF, DOC, DOCX — max 20 MB</p>
                    </div>
                    <input type="file" id="file" name="file" accept=".pdf,.doc,.docx" style="display:none;"
                           onchange="updateFileLabel(this)">
                    @error('file')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                {{-- ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route(isset($researchProject) ? 'research.show' : 'research.my-projects', isset($researchProject) ? $researchProject : []) }}"
                       class="btn-ghost">Cancel</a>
                    <button type="submit" class="btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        {{ isset($researchProject) ? 'Update & Resubmit' : 'Submit Research' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const abstractEl = document.getElementById('abstract');
    const countEl    = document.getElementById('abstract-count');

    function updateCount() {
        const n = abstractEl.value.length;
        countEl.textContent = n.toLocaleString() + ' characters';
        countEl.style.color = n < 100 ? '#ef4444' : '#94a3b8';
    }
    abstractEl.addEventListener('input', updateCount);
    updateCount();

    function updateFileLabel(input) {
        if (input.files.length) {
            document.getElementById('drop-label').innerHTML =
                '<strong style="color:#0f172a;">' + input.files[0].name + '</strong> selected';
        }
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('drop-zone').classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('file').files = dt.files;
            document.getElementById('drop-label').innerHTML =
                '<strong style="color:#0f172a;">' + file.name + '</strong> ready to upload';
        }
    }
</script>
@endpush
@endsection