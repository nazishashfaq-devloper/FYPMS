<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - VUFYPMS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    @yield('head')
</head>
<body>

<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="document.getElementById('vufypmsSidebar').classList.remove('open'); this.classList.remove('show');"></div>

<div class="d-flex flex-column flex-md-row vufypms-shell">

    {{-- ================= SIDEBAR ================= --}}
    <nav class="vufypms-sidebar d-flex flex-column" id="vufypmsSidebar">
        <div class="brand"><i class="bi bi-mortarboard-fill me-2"></i>VUFYPMS</div>

        <div class="nav flex-column px-2 py-2 flex-grow-1">

            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                <i class="bi bi-house-door me-2"></i>Home
            </a>

            @if(auth()->user()->role == 'admin')
                <div class="nav-heading">Overview</div>
                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>

                <div class="nav-heading">User &amp; Team Management</div>
                <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="/admin/users">
                    <i class="bi bi-people me-2"></i>Users
                </a>
                <a class="nav-link {{ request()->is('admin/users/create') ? 'active' : '' }}" href="/admin/users/create">
                    <i class="bi bi-person-plus me-2"></i>Add User
                </a>
                <a class="nav-link {{ request()->is('admin/assign-supervisor') ? 'active' : '' }}" href="/admin/assign-supervisor">
                    <i class="bi bi-person-check me-2"></i>Assign Supervisor
                </a>

                <div class="nav-heading">Project Setup</div>
                <a class="nav-link {{ request()->is('admin/domains*') ? 'active' : '' }}" href="{{ route('admin.domains.index') }}">
                    <i class="bi bi-tags me-2"></i>Project Domains
                </a>
                <a class="nav-link {{ request()->is('admin/deadlines*') ? 'active' : '' }}" href="{{ route('admin.deadlines.index') }}">
                    <i class="bi bi-calendar-event me-2"></i>Deadlines
                </a>
                <a class="nav-link {{ request()->is('admin/milestones*') ? 'active' : '' }}" href="{{ route('admin.milestones.index') }}">
                    <i class="bi bi-flag me-2"></i>Milestones
                </a>
                <a class="nav-link {{ request()->is('admin/presentations*') ? 'active' : '' }}" href="{{ route('admin.presentations.index') }}">
                    <i class="bi bi-easel me-2"></i>Presentations
                </a>
                <a class="nav-link {{ request()->is('admin/announcements*') ? 'active' : '' }}" href="/admin/announcements">
                    <i class="bi bi-megaphone me-2"></i>Announcements
                </a>

                <div class="nav-heading">Reports</div>
                <a class="nav-link {{ request()->is('admin/reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                    <i class="bi bi-bar-chart me-2"></i>View Reports
                </a>
            @endif


            @if(auth()->user()->role == 'supervisor')
                <div class="nav-heading">Overview</div>
                <a class="nav-link {{ request()->is('supervisor/dashboard') ? 'active' : '' }}" href="/supervisor/dashboard">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>

                <div class="nav-heading">Teams</div>
                <a class="nav-link {{ request()->is('supervisor/teams') ? 'active' : '' }}" href="/supervisor/teams">
                    <i class="bi bi-people me-2"></i>My Teams
                </a>
                <a class="nav-link {{ request()->is('supervisor/proposals') ? 'active' : '' }}" href="/supervisor/proposals">
                    <i class="bi bi-file-text me-2"></i>Proposal Review
                </a>
                <a class="nav-link {{ request()->is('supervisor/documents') ? 'active' : '' }}" href="/supervisor/documents">
                    <i class="bi bi-file-earmark-check me-2"></i>Documents Review
                </a>
                <a class="nav-link {{ request()->is('supervisor/milestones*') ? 'active' : '' }}" href="{{ route('supervisor.milestones.index') }}">
                    <i class="bi bi-flag me-2"></i>Milestones
                </a>
                <a class="nav-link {{ request()->is('supervisor/meetings') ? 'active' : '' }}" href="/supervisor/meetings">
                    <i class="bi bi-camera-video me-2"></i>Meetings
                </a>
                <a class="nav-link {{ request()->is('supervisor/evaluations') ? 'active' : '' }}" href="/supervisor/evaluations">
                    <i class="bi bi-clipboard-check me-2"></i>Evaluations
                </a>
                <a class="nav-link {{ request()->is('supervisor/messages*') ? 'active' : '' }}" href="{{ route('supervisor.messages.index') }}">
                    <i class="bi bi-chat-dots me-2"></i>Messages
                </a>

                <div class="nav-heading">Public Info</div>
                <a class="nav-link" href="/announcements"><i class="bi bi-megaphone me-2"></i>Announcements</a>
                <a class="nav-link" href="/deadlines"><i class="bi bi-calendar-event me-2"></i>Deadlines</a>
            @endif


            @if(auth()->user()->role == 'student')
                <div class="nav-heading">Overview</div>
                <a class="nav-link {{ request()->is('student/dashboard') ? 'active' : '' }}" href="/student/dashboard">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>

                <div class="nav-heading">My Project</div>
                <a class="nav-link {{ request()->is('team/dashboard') || request()->is('team/create') || request()->is('team/invite*') ? 'active' : '' }}" href="{{ route('team.dashboard') }}">
                    <i class="bi bi-people me-2"></i>My Team
                </a>
                <a class="nav-link {{ request()->is('student/proposal*') ? 'active' : '' }}" href="{{ route('proposal.index') }}">
                    <i class="bi bi-file-text me-2"></i>Proposal
                </a>
                <a class="nav-link {{ request()->is('student/documents') ? 'active' : '' }}" href="/student/documents">
                    <i class="bi bi-file-earmark-arrow-up me-2"></i>Documents
                </a>
                <a class="nav-link {{ request()->is('student/milestones') ? 'active' : '' }}" href="/student/milestones">
                    <i class="bi bi-flag me-2"></i>Milestones
                </a>
                <a class="nav-link {{ request()->is('student/presentation') ? 'active' : '' }}" href="{{ route('student.presentation') }}">
                    <i class="bi bi-easel me-2"></i>Presentation Schedule
                </a>
                <a class="nav-link {{ request()->is('student/evaluation-history') ? 'active' : '' }}" href="{{ route('evaluation.history') }}">
                    <i class="bi bi-clipboard-check me-2"></i>Evaluation History
                </a>
                <a class="nav-link {{ request()->is('student/messages') ? 'active' : '' }}" href="{{ route('student.messages') }}">
                    <i class="bi bi-chat-dots me-2"></i>Messages
                </a>

                <div class="nav-heading">Public Info</div>
                <a class="nav-link" href="/announcements"><i class="bi bi-megaphone me-2"></i>Announcements</a>
                <a class="nav-link" href="/deadlines"><i class="bi bi-calendar-event me-2"></i>Deadlines</a>
            @endif
        </div>

        <div class="p-2 border-top border-secondary">
            <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                <i class="bi bi-person-gear me-2"></i>My Profile
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <a href="{{ route('logout') }}" class="nav-link text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
        </div>
    </nav>

    {{-- ================= MAIN ================= --}}
    <div class="vufypms-main">
        <div class="vufypms-topbar d-flex align-items-center justify-content-between px-4 py-2">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-secondary mobile-menu-btn" type="button"
                        onclick="document.getElementById('vufypmsSidebar').classList.add('open'); document.getElementById('sidebarBackdrop').classList.add('show');">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
            </div>
            <div class="text-end">
                <a href="{{ route('profile.edit') }}" class="fw-semibold text-decoration-none text-dark">{{ auth()->user()->name }}</a>
                <br>
                <span class="badge badge-role role-{{ auth()->user()->role }}">{{ auth()->user()->role }}</span>
            </div>
        </div>

        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('#vufypmsSidebar a.nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            document.getElementById('vufypmsSidebar').classList.remove('open');
            document.getElementById('sidebarBackdrop').classList.remove('show');
        });
    });
</script>
@yield('scripts')
</body>
</html>
