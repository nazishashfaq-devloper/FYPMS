@extends('layouts.public')
@section('title', 'Home')
@section('content')

<style>
    /* ===============================================================
       Scoped styles for the PUBLIC welcome/landing page only.
       Prefixed "vu-pub-" — does not touch theme.css, dashboards,
       or auth pages. Colors match the existing palette.
       =============================================================== */

    .vu-pub-hero {
        position: relative;
        overflow: hidden;
        animation: vu-pub-fade-up .5s ease both;
    }
    .vu-pub-hero::before {
        content: "";
        position: absolute;
        top: -70px;
        right: -70px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(129,140,248,.35), transparent 70%);
        pointer-events: none;
    }
    .vu-pub-hero::after {
        content: "";
        position: absolute;
        bottom: -90px;
        left: 20%;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(139,92,246,.25), transparent 70%);
        pointer-events: none;
    }
    .vu-pub-hero > * { position: relative; z-index: 1; }
    .vu-pub-hero .btn {
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .vu-pub-hero .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(0,0,0,.2);
    }

    /* ---------- Feature tiles ---------- */
    .vu-pub-features {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.1rem;
    }
    @media (max-width: 991.98px) {
        .vu-pub-features { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575.98px) {
        .vu-pub-features { grid-template-columns: 1fr; }
    }
    /* Slight vertical stagger so the row doesn't look like a flat grid */
    .vu-pub-features > *:nth-child(2) { margin-top: 1.25rem; }
    .vu-pub-features > *:nth-child(4) { margin-top: 1.25rem; }
    @media (max-width: 575.98px) {
        .vu-pub-features > *:nth-child(2),
        .vu-pub-features > *:nth-child(4) { margin-top: 0; }
    }

    .vu-pub-tile {
        --vu-pub-accent: var(--vu-primary, #4f46e5);
        --vu-pub-accent-2: var(--vu-primary-light, #818cf8);
        position: relative;
        overflow: hidden;
        height: 100%;
        border: 1px solid var(--vu-card-border, #ebedf5);
        border-radius: 1rem;
        text-align: center;
        padding: 2rem 1.25rem 1.75rem;
        transform: translateY(0);
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        animation: vu-pub-fade-up .5s ease both;
    }
    .vu-pub-tile:nth-child(1) { animation-delay: .05s; }
    .vu-pub-tile:nth-child(2) { animation-delay: .12s; }
    .vu-pub-tile:nth-child(3) { animation-delay: .19s; }
    .vu-pub-tile:nth-child(4) { animation-delay: .26s; }

    .vu-pub-tile::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, var(--vu-pub-accent), var(--vu-pub-accent-2));
        opacity: 0;
        transition: opacity .22s ease;
        z-index: 0;
    }
    .vu-pub-tile:hover {
        transform: translateY(-8px);
        border-color: transparent;
        box-shadow: 0 18px 34px -14px rgba(30,27,75,.35);
    }
    .vu-pub-tile:hover::before { opacity: 1; }
    .vu-pub-tile > * { position: relative; z-index: 1; }

    .vu-pub-tile-icon {
        width: 3.5rem;
        height: 3.5rem;
        margin: 0 auto;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: #fff;
        background: linear-gradient(135deg, var(--vu-pub-accent), var(--vu-pub-accent-2));
        transition: transform .25s ease;
    }
    .vu-pub-tile:hover .vu-pub-tile-icon {
        transform: scale(1.1) rotate(-4deg);
    }

    .vu-pub-tile h5 {
        margin-top: 1.1rem;
        font-weight: 700;
        transition: color .22s ease;
    }
    .vu-pub-tile p {
        transition: color .22s ease;
    }
    .vu-pub-tile:hover h5,
    .vu-pub-tile:hover p { color: #fff !important; }

    .vu-pub-tile .btn {
        position: relative;
        transition: background .22s ease, border-color .22s ease, color .22s ease;
    }
    .vu-pub-tile:hover .btn {
        background: #fff;
        border-color: #fff;
        color: var(--vu-pub-accent);
    }

    .vu-pub-tile.accent-warning { --vu-pub-accent: var(--vu-warning, #f59e0b); --vu-pub-accent-2: #fbbf24; }
    .vu-pub-tile.accent-info    { --vu-pub-accent: var(--vu-info, #0ea5e9);    --vu-pub-accent-2: #38bdf8; }
    .vu-pub-tile.accent-success { --vu-pub-accent: var(--vu-success, #10b981); --vu-pub-accent-2: #34d399; }
    .vu-pub-tile.accent-violet  { --vu-pub-accent: var(--vu-violet, #8b5cf6);  --vu-pub-accent-2: #a78bfa; }

    /* ---------- Role panels ---------- */
    .vu-pub-roles {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.1rem;
    }
    @media (max-width: 767.98px) {
        .vu-pub-roles { grid-template-columns: 1fr; }
    }
    .vu-pub-role {
        background: #fff;
        border: 1px solid var(--vu-card-border, #ebedf5);
        border-radius: 1rem;
        padding: 1.5rem;
        border-left: 4px solid var(--vu-pub-role-accent, var(--vu-primary));
        transition: transform .2s ease, box-shadow .2s ease, border-left-color .2s ease;
    }
    .vu-pub-role:hover {
        transform: translateX(4px) translateY(-3px);
        box-shadow: 0 12px 24px -10px rgba(30,27,75,.25);
    }
    .vu-pub-role h5 {
        font-weight: 700;
        display: flex;
        align-items: center;
    }
    .vu-pub-role h5 i {
        color: var(--vu-pub-role-accent, var(--vu-primary));
        transition: transform .2s ease;
    }
    .vu-pub-role:hover h5 i { transform: scale(1.15); }
    .vu-pub-role.role-supervisor { --vu-pub-role-accent: var(--vu-info, #0ea5e9); }
    .vu-pub-role.role-admin      { --vu-pub-role-accent: var(--vu-violet, #8b5cf6); }

    @keyframes vu-pub-fade-up {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (prefers-reduced-motion: reduce) {
        .vu-pub-hero, .vu-pub-tile { animation: none; }
        .vu-pub-tile, .vu-pub-tile-icon, .vu-pub-role, .vu-pub-hero .btn { transition: none; }
    }
</style>

<div class="p-5 mb-5 rounded-4 text-white vu-pub-hero" style="background:linear-gradient(135deg,#1e1b4b,#4f46e5);">
    <h1 class="display-6 fw-bold">Virtual University Final Year Project Management System</h1>
    <p class="lead mb-4">A centralized portal for students, supervisors, and administrators to manage FYP proposals, teams, milestones, documents, and evaluations &mdash; from kickoff to final defense.</p>
    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">Get Started</a>
    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
</div>

<div class="vu-pub-features">
    <div class="vu-pub-tile">
        <div class="vu-pub-tile-icon"><i class="bi bi-journal-text"></i></div>
        <h5>Project Guidelines</h5>
        <p class="text-muted small">FYP rules, timelines, and submission policies.</p>
        <a href="{{ route('public.guidelines') }}" class="btn btn-sm btn-outline-primary">View Guidelines</a>
    </div>
    <div class="vu-pub-tile accent-violet">
        <div class="vu-pub-tile-icon"><i class="bi bi-megaphone"></i></div>
        <h5>Announcements</h5>
        <p class="text-muted small">Latest notices and schedule updates.</p>
        <a href="{{ route('public.announcements') }}" class="btn btn-sm btn-outline-primary">View Announcements</a>
    </div>
    <div class="vu-pub-tile accent-info">
        <div class="vu-pub-tile-icon"><i class="bi bi-calendar-event"></i></div>
        <h5>Deadlines</h5>
        <p class="text-muted small">Upcoming proposal and submission deadlines.</p>
        <a href="{{ route('public.deadlines') }}" class="btn btn-sm btn-outline-primary">View Deadlines</a>
    </div>
    <div class="vu-pub-tile accent-success">
        <div class="vu-pub-tile-icon"><i class="bi bi-search"></i></div>
        <h5>Browse Projects</h5>
        <p class="text-muted small">Search approved projects from past semesters.</p>
        <a href="{{ route('public.projects.search') }}" class="btn btn-sm btn-outline-primary">Search Projects</a>
    </div>
</div>

<div class="vu-pub-roles mt-5">
    <div class="vu-pub-role role-student">
        <h5><i class="bi bi-person-circle me-2"></i>Students</h5>
        <p class="text-muted mb-0">Form a team, submit your proposal, upload deliverables, track milestones, and stay in touch with your supervisor.</p>
    </div>
    <div class="vu-pub-role role-supervisor">
        <h5><i class="bi bi-person-badge me-2"></i>Supervisors</h5>
        <p class="text-muted mb-0">Review proposals, monitor progress, schedule meetings, and record evaluations for every team you supervise.</p>
    </div>
    <div class="vu-pub-role role-admin">
        <h5><i class="bi bi-gear me-2"></i>Administrators</h5>
        <p class="text-muted mb-0">Manage users, domains, deadlines, supervisor allocation, and generate system-wide reports.</p>
    </div>
</div>
@endsection