<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'VUFYPMS') - Virtual University FYP Management System</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    <style>
        footer { color:#9694c4; font-size:.85rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background:linear-gradient(135deg,#1e1b4b,#312e81);">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="bi bi-mortarboard-fill me-2"></i>VUFYPMS
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('public.guidelines') }}">Guidelines</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('public.announcements') }}">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('public.deadlines') }}">Deadlines</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('public.projects.search') }}">Browse Projects</a></li>

                @auth
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-light btn-sm" href="{{ url('/') }}">Dashboard</a>
                    </li>
                @else
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-light btn-sm" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</main>

<footer class="text-center py-4">
    &copy; {{ date('Y') }} Virtual University of Pakistan &mdash; Final Year Project Management System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
