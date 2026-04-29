<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ProjectWise') — Research Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=DM+Serif+Display:ital@0;1&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --ink:      #0f1117;
            --ink-soft: #3d4151;
            --ink-mute: #8a8f9e;
            --surface:  #f7f7f5;
            --card:     #ffffff;
            --border:   #e4e4e0;
            --accent:   #2d5be3;
            --accent-h: #1e45c8;
            --accent-tint: #eef1fd;
            --success:  #059669;
            --warning:  #d97706;
            --danger:   #dc2626;
            --sidebar-w: 240px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--ink);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        h1,h2,h3,h4 { font-family: 'DM Serif Display', serif; line-height: 1.2; }
        code, .mono { font-family: 'JetBrains Mono', monospace; }
 
        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--ink);
            color: #c8cadb;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
            transition: transform .25s cubic-bezier(.4,0,.2,1);
        }
        #sidebar .logo {
            padding: 1.5rem 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .logo-text {
            font-family: 'DM Serif Display', serif;
            font-size: 1.35rem;
            color: #fff;
            letter-spacing: -0.01em;
        }
        #sidebar .logo-sub {
            font-size: .65rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #6b7280;
            margin-top: 1px;
        }
        #sidebar nav { flex: 1; padding: 1rem 0; }
        #sidebar .nav-section {
            font-size: .625rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #4b5563;
            padding: .75rem 1.5rem .35rem;
        }
        #sidebar a.nav-link {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .55rem 1.5rem;
            font-size: .875rem;
            font-weight: 400;
            color: #9ca3af;
            text-decoration: none;
            transition: color .15s, background .15s;
            border-left: 2px solid transparent;
        }
        #sidebar a.nav-link:hover   { color: #fff; background: rgba(255,255,255,.04); }
        #sidebar a.nav-link.active  { color: #fff; background: rgba(45,91,227,.15); border-left-color: var(--accent); }
        #sidebar a.nav-link svg     { width: 16px; height: 16px; flex-shrink: 0; }
        #sidebar .user-section {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        #sidebar .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        #sidebar .user-name  { font-size: .8rem; font-weight: 500; color: #e5e7eb; }
        #sidebar .user-role  { font-size: .7rem; color: #6b7280; text-transform: capitalize; }
 
        /* ── Main ── */
        #main { margin-left: var(--sidebar-w); min-height: 100vh; }
 
        /* ── Topbar ── */
        #topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: .875rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0;
            z-index: 30;
        }
        #topbar .page-title { font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 600; color: var(--ink); }
        .topbar-actions { display: flex; align-items: center; gap: .75rem; }
 
        /* ── Content ── */
        #content { padding: 2rem; max-width: 1280px; }
 
        /* ── Alert / Flash ── */
        .flash {
            padding: .75rem 1.25rem;
            border-radius: 8px;
            font-size: .875rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.5rem;
        }
        .flash-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .flash-info    { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
 
        /* ── Cards ── */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-body { padding: 1.5rem; }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h3 { font-family: 'DM Sans', sans-serif; font-size: .9375rem; font-weight: 600; margin: 0; }
 
        /* ── Stat card ── */
        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 110px;
        }
        .stat-label { font-size: .75rem; letter-spacing: .05em; text-transform: uppercase; color: var(--ink-mute); font-weight: 500; }
        .stat-value { font-family: 'DM Serif Display', serif; font-size: 2rem; color: var(--ink); line-height: 1; align-self: flex-end; }
 
        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
            padding: .5rem 1.125rem;
            border-radius: 8px;
            font-size: .875rem; font-weight: 500;
            cursor: pointer; border: none;
            text-decoration: none;
            transition: background .15s, box-shadow .15s, transform .1s;
            white-space: nowrap;
        }
        .btn:active { transform: scale(.98); }
        .btn-primary   { background: var(--accent);   color: #fff; }
        .btn-primary:hover { background: var(--accent-h); }
        .btn-ghost  { background: transparent; color: var(--ink-soft); border: 1px solid var(--border); }
        .btn-ghost:hover { background: var(--surface); }
        .btn-danger { background: #fef2f2; color: var(--danger); border: 1px solid #fecaca; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-sm { padding: .35rem .75rem; font-size: .8125rem; }
        .btn-icon { padding: .45rem; border-radius: 7px; }
 
        /* ── Status badges ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: .2rem .65rem;
            border-radius: 99px;
            font-size: .72rem; font-weight: 600; letter-spacing: .02em;
            background: transparent !important;
        }
        .badge-pending   { color: #b45309; }
        .badge-approved  { color: #059669; }
        .badge-rejected  { color: #dc2626; }
        .badge-review    { color: #2563eb; }
        .badge-draft     { color: #6b7280; }
        .badge-revision  { color: #ea580c; }
 
        /* ── Forms ── */
        .form-label { display: block; font-size: .8125rem; font-weight: 500; color: var(--ink-soft); margin-bottom: .4rem; }
        .form-input {
            width: 100%;
            padding: .55rem .875rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: .875rem;
            font-family: inherit;
            color: var(--ink);
            background: var(--card);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-tint); }
        .form-input::placeholder { color: var(--ink-mute); }
        .form-error { font-size: .78rem; color: var(--danger); margin-top: .3rem; }
        textarea.form-input { resize: vertical; min-height: 100px; line-height: 1.6; }
        select.form-input { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a8f9e' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .75rem center; appearance: none; padding-right: 2.25rem; }
 
        /* ── Tables ── */
        .table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .table th { text-align: left; padding: .75rem 1rem; font-size: .72rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--ink-mute); border-bottom: 1px solid var(--border); }
        .table td { padding: .875rem 1rem; border-bottom: 1px solid #f3f4f6; color: var(--ink-soft); vertical-align: middle; }
        .table tbody tr:hover td { background: var(--surface); }
        .table tbody tr:last-child td { border-bottom: none; }
 
        /* ── Notification dot ── */
        .notif-dot { width: 7px; height: 7px; background: var(--danger); border-radius: 50%; flex-shrink: 0; }
 
        /* ── Responsive ── */
        @media (max-width: 1024px) {
            #sidebar { width: 200px; }
            #main { margin-left: 200px; }
            #topbar { padding: 0.75rem 1.5rem; }
            #content { padding: 1.5rem; }
        }
        @media (max-width: 768px) {
            :root { --sidebar-w: 0; }
            #sidebar { 
                width: 75vw; 
                max-width: 280px;
                transform: translateX(-100%); 
                box-shadow: 2px 0 12px rgba(0,0,0,0.1);
            }
            #sidebar.open { transform: translateX(0); }
            #main { margin-left: 0; }
            #topbar { padding: 0.75rem 1rem; }
            #topbar .page-title { font-size: 0.9rem; }
            #content { padding: 1rem; }
            .card-body { padding: 1rem; }
            .card-header { padding: 1rem; }
            .btn { padding: 0.45rem 0.875rem; font-size: 0.8rem; }
            .form-input, select.form-input { padding: 0.5rem 0.75rem; font-size: 1rem; }
            .table { font-size: 0.75rem; }
            .table th, .table td { padding: 0.5rem; }
            h1 { font-size: 1.25rem !important; }
            h2 { font-size: 1.1rem !important; }
            h3 { font-size: 1rem !important; }
        }
        @media (max-width: 480px) {
            :root { --sidebar-w: 0; }
            #topbar { padding: 0.625rem 0.75rem; }
            #topbar .page-title { font-size: 0.85rem; }
            #content { padding: 0.75rem; }
            .card-body { padding: 0.875rem; }
            .card-header { padding: 0.875rem; }
            .btn { padding: 0.4rem 0.75rem; font-size: 0.75rem; }
            .form-input, select.form-input { padding: 0.45rem 0.625rem; font-size: 1rem; }
            .stat-card { padding: 1rem; }
            .stat-value { font-size: 1.5rem; }
            .badge { padding: 0.15rem 0.5rem; font-size: 0.65rem; }
        }
    </style>
    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
 
{{-- Sidebar --}}
<aside id="sidebar" :class="{ 'open': sidebarOpen }">
    <div class="logo">
        <div style="display:flex;align-items:center;gap:.875rem;">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#2d5be3,#4f7bff);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(45,91,227,.4);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            <div>
                <div class="logo-text">ProjectWise</div>
                <div class="logo-sub">Research Management System</div>
            </div>
        </div>
    </div>
 
    <nav>
        @php $role = auth()->user()->role; @endphp
 
        <div class="nav-section">Overview</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            Dashboard
        </a>
 
        <div class="nav-section">Research</div>
        <a href="{{ route('research.index') }}" class="nav-link {{ request()->routeIs('research.index') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            Explorer
        </a>
 
        @if($role === 'student')
        <a href="{{ route('research.my-projects') }}" class="nav-link {{ request()->routeIs('research.my-projects') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            My Projects
        </a>
        <a href="{{ route('research.create') }}" class="nav-link {{ request()->routeIs('research.create') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
            Submit Research
        </a>
        @endif
 
        @if(in_array($role, ['student', 'faculty']))
        <a href="{{ route('saved.index') }}" class="nav-link {{ request()->routeIs('saved.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
            Saved Library
        </a>
        @endif
 
        @if(in_array($role, ['faculty', 'admin']))
        <div class="nav-section">Faculty</div>
        <a href="{{ route('faculty.explorer') }}" class="nav-link {{ request()->routeIs('faculty.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.95 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.86 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            Review Queue
        </a>
        @endif
 
        @if($role === 'admin')
        <div class="nav-section">Administration</div>
        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Users
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            Reports
        </a>
        <a href="{{ route('admin.activity') }}" class="nav-link {{ request()->routeIs('admin.activity') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Activity Log
        </a>
        @endif
 
        <div class="nav-section">Account</div>
        <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profile & Settings
        </a>
    </nav>
 
    <div class="user-section">
        @php $avatarUrl = auth()->user()->avatar_url; @endphp
        @if($avatarUrl)
            <img src="{{ asset($avatarUrl) }}" alt="" class="user-avatar">
        @else
            <div class="user-avatar" style="background:#2d5be3;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        @endif
        <div style="min-width:0;">
            <div class="user-name truncate">{{ auth()->user()->full_name ?? auth()->user()->name }}</div>
            <div class="user-role">{{ auth()->user()->role }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-left:auto; flex-shrink:0;">
            @csrf
            <button type="submit" title="Logout" style="background:none;border:none;cursor:pointer;color:#4b5563;padding:.25rem;transition:color .15s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#4b5563'">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
        </form>
    </div>
</aside>
 
{{-- Overlay on mobile --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:39;"
     x-transition:enter="transition-opacity duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"></div>
 
{{-- Main content --}}
<div id="main">
    <div id="topbar">
        <div style="display:flex;align-items:center;gap:1rem;">
            <button @click="sidebarOpen=!sidebarOpen" class="btn btn-ghost btn-icon" style="display:none;" id="menu-toggle">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topbar-actions">
            {{-- Notifications Bell --}}
            <div x-data="{ open: false }" style="position:relative;">
                <button @click="open=!open" class="btn btn-ghost btn-icon" style="position:relative;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    @if(auth()->user()->unreadNotifications->count())
                    <span style="position:absolute;top:5px;right:5px;width:7px;height:7px;background:#dc2626;border-radius:50%;border:2px solid #fff;"></span>
                    @endif
                </button>
                <div x-show="open" @click.outside="open=false" x-transition
                     style="position:absolute;right:0;top:calc(100% + .5rem);width:320px;background:#fff;border:1px solid var(--border);border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.1);z-index:50;overflow:hidden;">
                    <div style="padding:.875rem 1.125rem;border-bottom:1px solid var(--border);font-size:.8125rem;font-weight:600;color:var(--ink);">Notifications</div>
                    <div style="max-height:320px;overflow-y:auto;">
                        @forelse(auth()->user()->notifications()->latest()->take(8)->get() as $notif)
                        <div style="padding:.75rem 1.125rem;border-bottom:1px solid #f9fafb;display:flex;gap:.65rem;align-items:flex-start;{{ $notif->read_at ? '' : 'background:#f0f4ff;' }}">
                            @if(!$notif->read_at)<span class="notif-dot" style="margin-top:.35rem;flex-shrink:0;"></span>@endif
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:.8125rem;color:var(--ink-soft);">{{ $notif->data['message'] ?? 'New notification' }}</div>
                                <div style="font-size:.7rem;color:var(--ink-mute);margin-top:.2rem;">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @empty
                        <div style="padding:1.5rem;text-align:center;font-size:.8125rem;color:var(--ink-mute);">No notifications yet</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <div id="content">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="flash flash-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error') || $errors->any())
        <div class="flash flash-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') ?? $errors->first() }}
        </div>
        @endif
 
        @yield('content')
    </div>
</div>
 
<style>
@media (max-width: 768px) {
    #menu-toggle { display:flex !important; }
}
</style>
@stack('scripts')
</body>
</html>