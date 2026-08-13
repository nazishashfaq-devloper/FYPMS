@extends('layouts.dashboard')

@section('title', 'My Team')

@section('content')

@if(!$team)
    <div class="card shadow-sm" style="max-width:520px;">
        <div class="card-body text-center">
            <p class="text-muted">You are not part of a team yet.</p>
            <a href="{{ route('team.create') }}" class="btn btn-primary">Create a Team</a>
        </div>
    </div>
@else

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">{{ $team->team_name }}</h4>
                <span class="text-muted">Supervisor: {{ $team->supervisor->name ?? 'Not yet assigned' }}</span>
            </div>
            @if(Auth::id() == $team->leader_id && count($members) < 3)
                <a href="/team/invite" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus me-1"></i>Invite Members
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Team Members</div>
        <div class="card-body p-0">
            <div class="table-responsive">
<table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Name</th><th>Email</th><th>Role</th></tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>{{ $member->student->name ?? 'Unknown' }}</td>
                            <td>{{ $member->student->email ?? '' }}</td>
                            <td>
                                @if($team->leader_id == $member->student_id)
                                    <span class="badge bg-primary">Leader</span>
                                @else
                                    <span class="badge bg-secondary">Member</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No members yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
</div>
        </div>
    </div>

    @if(Auth::id() == $team->leader_id)
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Pending Invitations</div>
            <div class="card-body p-0">
                <div class="table-responsive">
<table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>Invited Student</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($pending as $inv)
                            <tr>
                                <td>{{ $inv->student->name ?? 'Unknown' }}</td>
                                <td><span class="badge bg-warning text-dark">Awaiting Response</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">No pending invites.</td></tr>
                        @endforelse
                    </tbody>
                </table>
</div>
            </div>
        </div>
    @endif

@endif
@endsection
