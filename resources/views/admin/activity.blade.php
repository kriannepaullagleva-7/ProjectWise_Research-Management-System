@extends('layouts.app')
@section('title', 'Activity Log')
@section('page-title', 'Activity Log')

@section('content')
<div style="background: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h2 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 600;">System Activity</h2>

    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
        <button style="background: #2563eb; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;">All Activities</button>
        <button style="background: #e5e7eb; color: #374151; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;">Users</button>
        <button style="background: #e5e7eb; color: #374151; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;">Projects</button>
    </div>

    <div style="text-align: center; padding: 2rem; color: #6b7280;">
        <p style="margin: 0;">Activity logging is currently being developed.</p>
        <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">All user actions will be tracked here soon.</p>
    </div>
</div>
@endsection
