<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yslam Portaly — Admin Panel</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 240px;
            --header-height: 60px;
            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active-bg: #1e40af;
            --sidebar-active-text: #fff;
            --sidebar-hover-bg: #1e293b;
        }

        * { box-sizing: border-box; }

        html[data-bs-theme="dark"] {
            --bs-body-bg: #0f172a;
            --bs-body-color: #e2e8f0;
            --bs-card-bg: #1e293b;
            --bs-border-color: #334155;
        }

        body {
            min-height: 100vh;
            background-color: var(--bs-body-bg, #f1f5f9);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* ---- SIDEBAR ---- */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 18px 20px 16px;
            border-bottom: 1px solid #1e293b;
            text-decoration: none;
        }

        .sidebar-brand .brand-name {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
            line-height: 1;
        }

        .sidebar-brand .brand-sub {
            font-size: 11px;
            color: #3b82f6;
            font-weight: 500;
            margin-top: 3px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
            overflow-y: auto;
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #475569;
            padding: 8px 10px 4px;
            margin-top: 8px;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2px;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover-bg);
            color: #e2e8f0;
        }

        .sidebar .nav-link.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
        }

        .sidebar .nav-link svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: 0.8;
        }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid #1e293b;
        }

        .sidebar-footer .user-info {
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 8px;
            background: #1e293b;
        }

        .sidebar-footer .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
        }

        .sidebar-footer .user-role {
            font-size: 11px;
            color: #3b82f6;
            margin-top: 1px;
        }

        /* ---- MAIN CONTENT ---- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-header {
            height: var(--header-height);
            background: var(--bs-body-bg, #fff);
            border-bottom: 1px solid var(--bs-border-color, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            transition: background 0.2s;
        }

        html[data-bs-theme="light"] .top-header {
            background: #fff;
        }

        html[data-bs-theme="dark"] .top-header {
            background: #1e293b;
            border-color: #334155;
        }

        .page-content {
            padding: 24px;
            flex: 1;
        }

        /* ---- THEME TOGGLE ---- */
        .theme-toggle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid var(--bs-border-color, #e2e8f0);
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, border-color 0.2s;
            color: var(--bs-body-color, #374151);
            padding: 0;
        }

        .theme-toggle:hover {
            background: rgba(0,0,0,0.05);
        }

        html[data-bs-theme="dark"] .theme-toggle {
            border-color: #334155;
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .theme-toggle:hover {
            background: rgba(255,255,255,0.08);
        }

        /* ---- CARDS ---- */
        html[data-bs-theme="dark"] .card {
            background: #1e293b;
            border-color: #334155;
        }

        html[data-bs-theme="dark"] .card-header {
            background: #1e293b !important;
            border-color: #334155;
        }

        html[data-bs-theme="dark"] .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(255,255,255,0.03);
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .form-control,
        html[data-bs-theme="dark"] .form-select {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .form-control:focus,
        html[data-bs-theme="dark"] .form-select:focus {
            background: #0f172a;
            border-color: #3b82f6;
            color: #e2e8f0;
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.25);
        }

        html[data-bs-theme="dark"] .text-dark { color: #e2e8f0 !important; }
        html[data-bs-theme="dark"] .table a.text-dark { color: #e2e8f0 !important; }
        html[data-bs-theme="dark"] .text-muted { color: #94a3b8 !important; }
        html[data-bs-theme="dark"] .border-bottom { border-color: #334155 !important; }

        /* ---- ALERT ---- */
        html[data-bs-theme="dark"] .alert-success {
            background: #14532d;
            border-color: #166534;
            color: #bbf7d0;
        }

        html[data-bs-theme="dark"] .alert-danger {
            background: #450a0a;
            border-color: #991b1b;
            color: #fecaca;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-block text-decoration-none">
            <div class="brand-name">Yslam Portaly</div>
            <div class="brand-sub">Admin Panel</div>
        </a>

        <nav class="sidebar-nav">
            <div class="sidebar-label">Esasy</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                Dashboard
            </a>

            @if(in_array(auth()->user()->role, ['admin', 'editor', 'author']))
            <div class="sidebar-label">Mazmun</div>
            <a href="{{ route('admin.articles.index') }}"
               class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Statýalar
            </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'editor']))
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Kategoriýalar
            </a>

            <a href="{{ route('admin.tags.index') }}"
               class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Tegler
            </a>

            <a href="{{ route('admin.banners.index') }}"
               class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Bannerlar
            </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'editor', 'moderator']))
            <div class="sidebar-label">Moderasiýa</div>
            <a href="{{ route('admin.comments.index') }}"
               class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Kommentarlar
            </a>
            @endif

            @if(auth()->user()->role === 'admin')
            <div class="sidebar-label">Admin</div>
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Ulanyjylar
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role text-uppercase">{{ auth()->user()->role }}</div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Çykmak
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main-wrapper">
        <header class="top-header">
            <h5 class="m-0 fw-semibold" style="font-size: 15px;">@yield('header', 'Panel uprawlenya')</h5>

            <div class="d-flex align-items-center gap-3">
                <!-- Theme Toggle Button (Sun/Moon) -->
                <button class="theme-toggle" id="themeToggle" title="Tema üýtget" onclick="toggleTheme()">
                    <!-- Sun icon (shown in dark mode) -->
                    <svg id="iconSun" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <!-- Moon icon (shown in light mode) -->
                    <svg id="iconMoon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <div class="d-flex flex-column align-items-end">
                    <span class="fw-semibold" style="font-size:13px; line-height:1.2;">{{ auth()->user()->name }}</span>
                    <span class="text-primary" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">{{ auth()->user()->role }}</span>
                </div>
            </div>
        </header>

        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <strong>✓</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <strong>✗</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            const html = document.documentElement;
            const saved = localStorage.getItem('admin_theme') || 'light';
            applyTheme(saved);

            function applyTheme(theme) {
                html.setAttribute('data-bs-theme', theme);
                const sun = document.getElementById('iconSun');
                const moon = document.getElementById('iconMoon');
                if (sun && moon) {
                    sun.style.display = theme === 'dark' ? 'block' : 'none';
                    moon.style.display = theme === 'dark' ? 'none' : 'block';
                }
            }

            window.toggleTheme = function () {
                const current = html.getAttribute('data-bs-theme') || 'light';
                const next = current === 'light' ? 'dark' : 'light';
                localStorage.setItem('admin_theme', next);
                applyTheme(next);
            };
        })();
    </script>
</body>

</html>
