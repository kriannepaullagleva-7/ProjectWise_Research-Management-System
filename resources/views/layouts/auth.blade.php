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
            --warning: #d97706;
            --danger: #dc2626;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #f7f7f5 0%, #e8e8e5 100%);
            color: var(--ink);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'DM Serif Display', serif; line-height: 1.2; }

        /* Auth Container */
        .auth-container {
            width: 100%;
            max-width: 540px;
            padding: 2rem 1.5rem;
        }

        .auth-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: -0.01em;
            margin-bottom: 0.5rem;
        }

        .auth-tagline {
            font-size: 0.875rem;
            color: var(--ink-mute);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Form Styles */
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--ink);
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.9375rem;
            color: var(--ink);
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-tint);
        }

        .form-input::placeholder {
            color: var(--ink-mute);
        }

        .form-error {
            color: var(--danger);
            font-size: 0.8125rem;
            margin-top: 0.35rem;
            margin-bottom: 0;
        }

        /* Buttons */
        .btn-primary {
            width: 100%;
            background: var(--accent);
            color: white;
            padding: 0.875rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: var(--accent-h);
        }

        /* Links */
        .auth-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: var(--accent-h);
        }

        /* Divider */
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            color: var(--ink-mute);
            font-size: 0.875rem;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border);
        }

        .divider span {
            background: white;
            padding: 0 0.75rem;
            position: relative;
        }

        /* Row for two columns */
        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .auth-card {
                padding: 1.5rem;
            }

            .auth-logo {
                font-size: 1.5rem;
            }

            .row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>
</body>
</html>
