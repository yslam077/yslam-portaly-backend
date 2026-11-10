<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yslam Portaly — Admin Panel</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #1e293b;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            border-radius: 6px;
            margin-bottom: 4px;
            padding: 10px 14px;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #334155;
        }

        .content-wrapper {
            min-width: 0;
            flex-grow: 1;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <aside class="sidebar text-white p-3 d-flex flex-column">
            <div class="fs-4 fw-bold p-2 border-bottom border-secondary mb-3">
                Yslam Portaly
                <small class="d-block fs-6 text-primary font-monospace">Admin Panel</small>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>

                @if(in_array(auth()->user()->role, ['admin', 'editor', 'author']))
                <li>
                    <a href="{{ route('admin.articles.index') }}" class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                        Statya
                    </a>
                </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'editor']))
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        Categoryalar
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tags.index') }}" class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                        Teglar
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                        Banner
                    </a>
                </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'editor', 'moderator']))
                <li>
                    <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                        Kommentariyalar
                    </a>
                </li>
                @endif

                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        Ulanyjylar
                    </a>
                </li>
                @endif
            </ul>
            <div class="pt-3 border-top border-secondary">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        Cykmak
                    </button>
                </form>
            </div>
        </aside>

        <div class="content-wrapper">
            <header class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center shadow-sm">
                <h4 class="m-0 fw-semibold">@yield('header', 'Panel uprawlenya')</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-medium text-secondary">{{ auth()->user()->name }}</span>
                    <span class="badge bg-primary text-uppercase">{{ auth()->user()->role }}</span>
                </div>
            </header>

            <main class="p-4">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>