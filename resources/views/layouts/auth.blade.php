<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ProjectWise') — Research Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0f1117;
            --ink-soft: #3d4151;
            --ink-mute: #8a8f9e;
            --surface: #f7f7f5;
            --card: #ffffff;
            --border: #e4e4e0;
            --accent: #2d5be3;
            --accent-h: #1e45c8;
            --accent-tint: #eef1fd;
            --success: #059669;
            --danger: #dc2626;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; min-height: 100vh; font-family: 'DM Sans', sans-serif; color: var(--ink); -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4 { font-family: 'DM Serif Display', serif; line-height: 1.2; margin: 0; }

        /* Auth centered layout (used by register/forgot-password) */
        .auth-page-wrap {
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .auth-container { width: 100%; max-width: 520px; }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.25rem 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,.07);
            border: 1px solid var(--border);
        }
        .auth-header { text-align: center; margin-bottom: 1.75rem; }
        .auth-logo { font-family: 'DM Serif Display', serif; font-size: 1.75rem; color: var(--accent); letter-spacing: -.01em; margin-bottom: .25rem; }
        .auth-tagline { font-size: .75rem; color: var(--ink-mute); letter-spacing: .07em; text-transform: uppercase; }
        .auth-link { color: var(--accent); text-decoration: none; font-weight: 600; }
        .auth-link:hover { color: var(--accent-h); }

        /* Form classes shared by register/forgot-password */
        .form-label { display: block; font-size: .8125rem; font-weight: 600; color: var(--ink); margin-bottom: .4rem; }
        .form-input {
            width: 100%;
            padding: .7rem .9rem;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: .9375rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background: #fafafa;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .form-input:focus { border-color: var(--accent); background: #fff; box-shadow: 0 0 0 3px var(--accent-tint); }
        .form-input::placeholder { color: var(--ink-mute); }
        .form-error { color: var(--danger); font-size: .8rem; margin-top: .3rem; margin-bottom: 0; }
        .btn-primary {
            width: 100%;
            background: var(--accent);
            color: #fff;
            padding: .825rem;
            border: none;
            border-radius: 9px;
            font-weight: 600;
            font-size: .9375rem;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-primary:hover { background: var(--accent-h); }
        .btn-primary:disabled { opacity: .6; cursor: not-allowed; }
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 520px) { .row-2 { grid-template-columns: 1fr; } .auth-card { padding: 1.75rem 1.25rem; } }
    </style>
    @stack('styles')
</head>
<body>
@yield('content')
@stack('scripts')
</body>
</html>
