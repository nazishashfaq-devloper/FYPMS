@extends('layouts.dashboard')

@section('title', 'Student Dashboard')

@section('content')

@php
    $milestones = $milestones ?? collect();
    $meetings = $meetings ?? collect();
    $documents = $documents ?? collect();
    $evaluations = $evaluations ?? collect();
    $presentations = $presentations ?? collect();
    $isLeader = $team && $team->leader_id == auth()->id();
@endphp

<p class="text-muted">Welcome back, {{ auth()->user()->name }}.</p>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary"><i class="bi bi-flag-fill"></i></div>
            <div>
                <div class="stat-label">Milestones</div>
                <div class="stat-value">{{ $milestones->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-info"><i class="bi bi-camera-video-fill"></i></div>
            <div>
                <div class="stat-label">Upcoming Meetings</div>
                <div class="stat-value">{{ $meetings->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning"><i class="bi bi-file-earmark-check-fill"></i></div>
            <div>
                <div class="stat-label">Documents Uploaded</div>
                <div class="stat-value">{{ $documents->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-icon stat-icon-success"><i class="bi bi-clipboard-check-fill"></i></div>
            <div>
                <div class="stat-label">Evaluations</div>
                <div class="stat-value">{{ $evaluations->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ===== TEAM & PROPOSAL STATUS ===== --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-people-fill"></i>Team</div>
            <div class="card-body">
                @if($team)
                    <h5>{{ $team->team_name }}</h5>
                    <p class="text-muted mb-1">Supervisor:
                        {{ $team->supervisor->name ?? 'Not yet assigned' }}
                    </p>
                    <a href="{{ route('team.dashboard') }}" class="btn btn-sm btn-outline-primary mt-2">View Team Dashboard</a>
                @else
                    <p class="text-muted">You haven't created or joined a team yet.</p>
                    <a href="{{ route('team.create') }}" class="btn btn-sm btn-primary">Create Team</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-file-earmark-text-fill"></i>Proposal Status</div>
            <div class="card-body">
                @if($latestProposal)
                    <h5>{{ $latestProposal->title }}</h5>
                    <span class="badge bg-{{ $latestProposal->status == 'approved' ? 'success' : ($latestProposal->status == 'rejected' ? 'danger' : 'secondary') }} mb-2">
                        {{ ucfirst($latestProposal->status) }}
                    </span>

                    @if($latestProposal->feedback)
                        <p class="text-muted mb-2"><strong>Feedback:</strong> {{ $latestProposal->feedback }}</p>
                    @endif

                    @if($latestProposal->status == 'rejected' && $isLeader)
                        <a href="{{ route('proposal.edit', $latestProposal->id) }}" class="btn btn-sm btn-danger">Resubmit Proposal</a>
                    @endif

                    <a href="{{ route('proposal.index') }}" class="btn btn-sm btn-outline-secondary">View Details</a>
                @elseif($team)
                    <p class="text-muted">No proposal submitted yet.</p>
                    @if($isLeader)
                        <a href="{{ route('proposal.create') }}" class="btn btn-sm btn-primary">Submit Proposal</a>
                    @else
                        <p class="small text-muted mb-0">Only your team leader can submit the proposal.</p>
                    @endif
                @else
                    <p class="text-muted mb-0">Create or join a team first to submit a proposal.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== INVITATIONS ===== --}}
    @if($invitations->count() > 0)
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning-subtle fw-semibold"><i class="bi bi-envelope-paper-fill text-warning"></i>Pending Team Invitations</div>
                <div class="card-body">
                    @foreach($invitations as $inv)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span>Invitation to join: <strong>{{ $inv->team->team_name ?? 'Team #'.$inv->team_id }}</strong></span>
                            <div>
                                <form method="POST" action="{{ route('team.invite.accept', $inv->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('team.invite.reject', $inv->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Reject</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ===== NOTIFICATIONS ===== --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-bell-fill"></i>Notifications</div>
            <div class="card-body scroll-panel-sm">
                @forelse($notifications as $note)
                    <div class="border-bottom py-2">
                        <div class="fw-semibold">{{ $note->title }}</div>
                        <div class="small text-muted">{{ $note->message }}</div>
                        <div class="small text-muted">{{ $note->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No notifications.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== MILESTONES ===== --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-flag-fill"></i>Milestones</span>
                <a href="/student/milestones" class="small">View all &rarr;</a>
            </div>
            <div class="card-body scroll-panel-sm">
                @forelse($milestones as $m)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold">{{ $m->title }}</div>
                            <small class="text-muted">Due: {{ $m->due_date ? \Illuminate\Support\Carbon::parse($m->due_date)->format('d M Y') : 'N/A' }}</small>
                        </div>
                        <span class="badge bg-{{ $m->status == 'completed' ? 'success' : 'secondary' }}">{{ ucfirst($m->status) }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No milestones assigned yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== DOCUMENTS ===== --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-file-earmark-check-fill"></i>Documents</span>
                <a href="/student/documents" class="small">Upload &rarr;</a>
            </div>
            <div class="card-body scroll-panel-sm">
                @forelse($documents as $doc)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold">{{ ucfirst(str_replace('_',' ',$doc->document_type)) }}</div>
                            @if($doc->feedback)
                                <small class="text-muted">{{ $doc->feedback }}</small>
                            @endif
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

    {{-- ===== MEETINGS ===== --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-camera-video-fill"></i>Upcoming Meetings</div>
            <div class="card-body scroll-panel-sm">
                @forelse($meetings as $meeting)
                    <div class="border-bottom py-2">
                        <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($meeting->meeting_date)->format('d M Y') }} at {{ \Illuminate\Support\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</div>
                        <small class="text-muted">{{ $meeting->venue ?: $meeting->meeting_link ?: 'No venue specified' }}</small>
                    </div>
                @empty
                    <p class="text-muted mb-0">No meetings scheduled.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== PRESENTATIONS ===== --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-easel-fill"></i>Presentation Schedule</div>
            <div class="card-body scroll-panel-sm">
                @forelse($presentations as $p)
                    <div class="border-bottom py-2">
                        <div class="fw-semibold">{{ ucfirst(str_replace('_',' ',$p->phase)) }}</div>
                        <small class="text-muted">{{ \Illuminate\Support\Carbon::parse($p->presentation_date)->format('d M Y') }} at {{ \Illuminate\Support\Carbon::parse($p->presentation_time)->format('h:i A') }}</small><br>
                        <small class="text-muted">{{ $p->venue ?: $p->meeting_link ?: '—' }}</small>
                    </div>
                @empty
                    <p class="text-muted mb-0">No presentations scheduled yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== EVALUATIONS ===== --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-clipboard-check-fill"></i>Evaluation History</div>
            <div class="card-body p-0">
                <div class="table-responsive">
<table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Phase</th>
                            <th>Marks</th>
                            <th>Remarks</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                            <tr>
                                <td>{{ ucfirst($evaluation->phase) }}</td>
                                <td>{{ $evaluation->marks }}</td>
                                <td>{{ $evaluation->remarks }}</td>
                                <td>{{ $evaluation->created_at->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No evaluations recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
</div>
            </div>
        </div>
    </div>

</div>
@endsection
