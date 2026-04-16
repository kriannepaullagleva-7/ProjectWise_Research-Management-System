@extends('layouts.app')
@section('title', isset($researchProject) ? 'Edit Research' : 'Submit Research')
@section('page-title', isset($researchProject) ? 'Edit Submission' : 'Submit Research')
 
@section('content')
<div style="max-width:760px;">
    <div class="card">
        <div class="card-header">
            <h3>{{ isset($researchProject) ? 'Update Your Research' : 'New Research Submission' }}</h3>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ isset($researchProject) ? route('research.update', $researchProject) : route('research.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($researchProject)) @method('PUT') @endif
 
                <div style="margin-bottom:1.25rem;">
                    <label class="form-label" for="title">Research Title <span style="color:#dc2626;">*</span></label>
                    <input class="form-input" type="text" id="title" name="title"
                           value="{{ old('title', $researchProject->title ?? '') }}"
                           placeholder="Enter the full title of your research" required>
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>
 
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label" for="category">Category <span style="color:#dc2626;">*</span></label>
                        <select class="form-input" id="category" name="category" required>
                            <option value="">Select a category…</option>
                            @foreach(['Computer Science & IT','Engineering','Natural Sciences','Social Sciences','Humanities','Business & Economics','Health & Medicine','Education','Environmental Science','Mathematics','Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $researchProject->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label" for="field_of_study">Field of Study</label>
                        <input class="form-input" type="text" id="field_of_study" name="field_of_study"
                               value="{{ old('field_of_study', $researchProject->field_of_study ?? '') }}"
                               placeholder="e.g. Machine Learning, Sociology">
                    </div>
                </div>
 
                <div style="margin-bottom:1.25rem;">
                    <label class="form-label" for="abstract">Abstract <span style="color:#dc2626;">*</span></label>
                    <textarea class="form-input" id="abstract" name="abstract" rows="7"
                              placeholder="Provide a comprehensive summary of your research (minimum 100 characters)…" required>{{ old('abstract', $researchProject->abstract ?? '') }}</textarea>
                    <div style="display:flex;justify-content:space-between;margin-top:.3rem;">
                        @error('abstract')<p class="form-error">{{ $message }}</p>@enderror
                        <span id="abstract-count" style="font-size:.72rem;color:var(--ink-mute);margin-left:auto;">0 characters</span>
                    </div>
                </div>
 
                <div style="margin-bottom:1.25rem;">
                    <label class="form-label" for="keywords">Keywords</label>
                    <input class="form-input" type="text" id="keywords" name="keywords"
                           value="{{ old('keywords', $researchProject->keywords ?? '') }}"
                           placeholder="e.g. machine learning, neural networks, deep learning (comma separated)">
                    <p style="font-size:.72rem;color:var(--ink-mute);margin-top:.3rem;">Separate keywords with commas to improve discoverability.</p>
                </div>
 
                {{-- File Upload --}}
                <div style="margin-bottom:1.75rem;">
                    <label class="form-label">Research Document</label>
                    @if(isset($researchProject) && $researchProject->file_path)
                    <div style="background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:.875rem 1rem;margin-bottom:.75rem;display:flex;align-items:center;gap:.75rem;font-size:.8125rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2d5be3" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span style="font-weight:500;color:var(--ink);">{{ $researchProject->file_original_name }}</span>
                        <span style="color:var(--ink-mute);">· {{ $researchProject->file_size_formatted }}</span>
                        <span style="color:var(--ink-mute);margin-left:.25rem;">Upload a new file to replace.</span>
                    </div>
                    @endif
                    <div id="drop-zone" style="border:2px dashed var(--border);border-radius:10px;padding:2rem;text-align:center;cursor:pointer;transition:border-color .15s,background .15s;"
                         ondragover="this.style.borderColor='var(--accent)';this.style.background='var(--accent-tint)';event.preventDefault();"
                         ondragleave="this.style.borderColor='var(--border)';this.style.background='';"
                         ondrop="handleDrop(event)"
                         onclick="document.getElementById('file').click()">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--ink-mute)" stroke-width="1.5" style="margin:0 auto .75rem;display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p id="drop-label" style="font-size:.875rem;color:var(--ink-soft);margin-bottom:.35rem;">Drop your file here or <span style="color:var(--accent);font-weight:500;">browse</span></p>
                        <p style="font-size:.75rem;color:var(--ink-mute);">PDF, DOC, DOCX — max 20 MB</p>
                    </div>
                    <input type="file" id="file" name="file" accept=".pdf,.doc,.docx" style="display:none;" onchange="updateFileLabel(this)">
                    @error('file')<p class="form-error">{{ $message }}</p>@enderror
                </div>
 
                <div style="display:flex;gap:.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid var(--border);">
                    <a href="{{ route(isset($researchProject) ? 'research.show' : 'research.my-projects', isset($researchProject) ? $researchProject : []) }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
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
        countEl.textContent = n + ' characters';
        countEl.style.color = n < 100 ? '#dc2626' : 'var(--ink-mute)';
    }
    abstractEl.addEventListener('input', updateCount);
    updateCount();
 
    function updateFileLabel(input) {
        if (input.files.length) {
            document.getElementById('drop-label').innerHTML =
                '<strong style="color:var(--ink);">' + input.files[0].name + '</strong> selected';
        }
    }
    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('drop-zone').style.borderColor = 'var(--border)';
        document.getElementById('drop-zone').style.background = '';
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('file').files = dt.files;
            document.getElementById('drop-label').innerHTML =
                '<strong style="color:var(--ink);">' + file.name + '</strong> ready to upload';
        }
    }
</script>
@endpush
@endsection