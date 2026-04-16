@extends('layouts.app')
@section('title', 'My Projects')
@section('page-title', 'My Projects')
 
@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem;">
    <a href="{{ route('research.create') }}" class="btn btn-primary">+ New Submission</a>
</div>
 
<div class="card">
    @if($projects->isEmpty())
    <div style="padding:4rem 2rem;text-align:center;color:var(--ink-mute);">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" style="margin:0 auto 1rem;display:block;opacity:.3;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <p style="font-size:.9rem;font-weight:500;color:var(--ink-soft);">No projects submitted yet.</p>
        <a href="{{ route('research.create') }}" style="display:inline-block;margin-top:.75rem;" class="btn btn-primary btn-sm">Submit your first research</a>
    </div>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Faculty Feedback</th>
                <th>Submitted</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $p)
            <tr>
                <td>
                    <a href="{{ route('research.show', $p) }}" style="font-weight:500;color:var(--ink);text-decoration:none;">{{ Str::limit($p->title, 55) }}</a>
                </td>
                <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $p->category }}</td>
                <td>
                    <span class="badge badge-{{ ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected','under_review'=>'review','revision_needed'=>'revision','draft'=>'draft'][$p->status] ?? 'draft' }}">{{ $p->status_label }}</span>
                </td>
                <td style="font-size:.8125rem;">
                    @if($p->latestFeedback)
                    <span style="color:var(--ink-soft);">{{ Str::limit($p->latestFeedback->comment, 50) }}</span>
                    <div style="font-size:.7rem;color:var(--ink-mute);">by {{ $p->latestFeedback->faculty->full_name }}</div>
                    @else
                    <span style="color:var(--ink-mute);">—</span>
                    @endif
                </td>
                <td style="font-size:.8125rem;color:var(--ink-mute);">{{ $p->created_at->format('M d, Y') }}</td>
                <td>
                    <div style="display:flex;gap:.4rem;">
                        <a href="{{ route('research.show', $p) }}" class="btn btn-ghost btn-sm">View</a>
                        @if(in_array($p->status, ['draft','revision_needed']))
                        <a href="{{ route('research.edit', $p) }}" class="btn btn-ghost btn-sm">Edit</a>
                        @endif
                        @if($p->status === 'draft')
                        <form method="POST" action="{{ route('research.destroy', $p) }}" onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);">
        {{ $projects->links() }}
    </div>
    @endif
</div>
@endsection