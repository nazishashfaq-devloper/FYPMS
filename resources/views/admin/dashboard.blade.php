@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')

@php
    $students = $students ?? 0;
    $supervisors = $supervisors ?? 0;
    $teams = $teams ?? 0;
    $proposals = $proposals ?? 0;
    $documents = $documents ?? 0;

    $allTeams = $allTeams ?? collect();
    $allProposals = $allProposals ?? collect();
    $allDocuments = $allDocuments ?? collect();
    $allUsers = $allUsers ?? collect();
    $evaluations = $evaluations ?? collect();
@endphp

<!-- ================= OVERVIEW STAT CARDS ================= -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <div class="stat-label">Students</div>
                <div class="stat-value">{{ $students }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-violet"><i class="bi bi-person-badge-fill"></i></div>
            <div>
                <div class="stat-label">Supervisors</div>
                <div class="stat-value">{{ $supervisors }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-info"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-label">Teams</div>
                <div class="stat-value">{{ $teams }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div>
                <div class="stat-label">Proposals</div>
                <div class="stat-value">{{ $proposals }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-success"><i class="bi bi-file-earmark-check-fill"></i></div>
            <div>
                <div class="stat-label">Documents</div>
                <div class="stat-value">{{ $documents }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ================= TEAMS ================= -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-people-fill"></i>Teams</div>
            <div class="card-body scroll-panel-md">
                @forelse($allTeams as $team)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold">{{ $team->team_name }}</div>
                            <small class="text-muted">Leader: {{ $team->leader->name ?? $team->leader_id }}</small>
                        </div>
                        <span class="badge {{ $team->supervisor_id ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $team->supervisor_id ? 'Supervisor Assigned' : 'Unassigned' }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No teams found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ================= PROPOSALS ================= -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-file-earmark-text-fill"></i>Proposals</div>
            <div class="card-body scroll-panel-md">
                @forelse($allProposals as $p)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold">{{ $p->title }}</div>
                            <small class="text-muted">{{ $p->domain }}</small>
                        </div>
                        <span class="badge bg-{{ $p->status == 'approved' ? 'success' : ($p->status == 'rejected' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No proposals</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ================= DOCUMENTS ================= -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-file-earmark-check-fill"></i>Documents</div>
            <div class="card-body scroll-panel-md">
                @forelse($allDocuments as $d)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold">{{ ucfirst(str_replace('_',' ',$d->document_type)) }}</div>
                            <small class="text-muted">Team #{{ $d->team_id }}</small>
                        </div>
                        <span class="badge bg-{{ $d->status == 'approved' ? 'success' : ($d->status == 'rejected' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($d->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No documents</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ================= USERS ================= -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-person-lines-fill"></i>Users</span>
                <a href="/admin/users" class="small">View all &rarr;</a>
            </div>
            <div class="card-body scroll-panel-md">
                @forelse($allUsers->take(8) as $u)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold">{{ $u->name }}</div>
                            <small class="text-muted">{{ $u->email }}</small>
                        </div>
                        <span class="badge bg-info text-dark text-uppercase">{{ $u->role }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No users</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- ================= EVALUATIONS ================= -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-clipboard-check-fill"></i>Recent Evaluations</span>
        <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-primary">View Reports</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Supervisor</th>
                    <th>Phase</th>
                    <th>Marks</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $evaluation)
                    <tr>
                        <td>{{ $evaluation->team->team_name ?? 'N/A' }}</td>
                        <td>{{ $evaluation->supervisor->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($evaluation->phase) }}</td>
                        <td>{{ $evaluation->marks }}</td>
                        <td>{{ $evaluation->remarks }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No evaluations recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>

@endsection
