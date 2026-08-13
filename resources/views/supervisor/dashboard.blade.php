@extends('layouts.dashboard')

@section('title', 'Supervisor Dashboard')

@section('content')

@php
    $teams = $teams ?? collect();
    $proposals = $proposals ?? collect();
    $documents = $documents ?? collect();
@endphp

<p class="text-muted">Welcome back, {{ auth()->user()->name }}.</p>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-info"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-label">Assigned Teams</div>
                <div class="stat-value">{{ $teams->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div>
                <div class="stat-label">Pending Proposal Reviews</div>
                <div class="stat-value">{{ $pendingProposals }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning"><i class="bi bi-file-earmark-check-fill"></i></div>
            <div>
                <div class="stat-label">Pending Documents</div>
                <div class="stat-value">{{ $pendingDocuments }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary"><i class="bi bi-flag-fill"></i></div>
            <div>
                <div class="stat-label">Upcoming Milestones</div>
                <div class="stat-value">{{ $upcomingMilestones }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-danger"><i class="bi bi-clipboard-check-fill"></i></div>
            <div>
                <div class="stat-label">Evaluations Pending</div>
                <div class="stat-value">{{ $evaluationWorkload }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ===== TEAMS ===== -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-people-fill"></i>My Teams</div>
            <div class="card-body scroll-panel-md">
                @forelse($teams as $team)
                    <div class="border-bottom py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $team->team_name }}</div>
                                <small class="text-muted">Leader: {{ $team->leader->name ?? $team->leader_id }}</small>
                            </div>
                            <div>
                                <a href="/supervisor/meetings/create/{{ $team->id }}" class="btn btn-sm btn-outline-primary">Schedule Meeting</a>
                                <a href="/supervisor/evaluation/{{ $team->id }}" class="btn btn-sm btn-outline-success">Evaluate</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No teams assigned yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== PROPOSALS ===== -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-file-earmark-text-fill"></i>Recent Proposals</span>
                <a href="/supervisor/proposals" class="small">View all &rarr;</a>
            </div>
            <div class="card-body scroll-panel-md">
                @forelse($proposals as $proposal)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold">{{ $proposal->title }}</div>
                            <small class="text-muted">{{ $proposal->domain }}</small>
                        </div>
                        <span class="badge bg-{{ $proposal->status == 'approved' ? 'success' : ($proposal->status == 'rejected' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($proposal->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No proposals found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== DOCUMENTS ===== -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-file-earmark-check-fill"></i>Recent Documents</span>
                <a href="/supervisor/documents" class="small">View all &rarr;</a>
            </div>
            <div class="card-body scroll-panel-md">
                @forelse($documents as $doc)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold">{{ ucfirst(str_replace('_',' ',$doc->document_type)) }}</div>
                            <small class="text-muted">Team #{{ $doc->team_id }}</small>
                        </div>
                        <span class="badge bg-{{ $doc->status == 'approved' ? 'success' : ($doc->status == 'rejected' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($doc->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No documents uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== DEADLINES ===== -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-calendar-event-fill"></i>Upcoming Deadlines</div>
            <div class="card-body scroll-panel-md">
                @forelse($deadlines as $deadline)
                    <div class="border-bottom py-2">
                        <div class="fw-semibold">{{ $deadline->title }}</div>
                        <small class="text-muted">{{ \Illuminate\Support\Carbon::parse($deadline->deadline_date)->format('d M Y') }}</small>
                    </div>
                @empty
                    <p class="text-muted mb-0">No deadlines published.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
