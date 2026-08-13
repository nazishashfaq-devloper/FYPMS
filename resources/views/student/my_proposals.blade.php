@extends('layouts.dashboard')

@section('title', 'My Proposal')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My Proposal</h4>
    @if($team && $proposals->isEmpty() && $team->leader_id == auth()->id())
        <a href="{{ route('proposal.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Submit Proposal
        </a>
    @endif
</div>

@if(!$team)
    <p class="text-muted">You need to create or join a team before submitting a proposal.</p>
    <a href="{{ route('team.create') }}" class="btn btn-primary btn-sm">Create Team</a>
@elseif($proposals->count() > 0)
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
<table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Feedback</th>
                        <th>Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proposals as $proposal)
                        <tr>
                            <td>{{ $proposal->title }}</td>
                            <td>{{ $proposal->domain }}</td>
                            <td>
                                <span class="badge bg-{{ $proposal->status == 'approved' ? 'success' : ($proposal->status == 'rejected' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($proposal->status) }}
                                </span>
                            </td>
                            <td>{{ $proposal->feedback ?? '—' }}</td>
                            <td>{{ $proposal->created_at->format('d-m-Y') }}</td>
                            <td>
                                @if($proposal->status == 'rejected' && $team->leader_id == auth()->id())
                                    <a href="{{ route('proposal.edit', $proposal->id) }}" class="btn btn-sm btn-outline-danger">Resubmit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
</div>
        </div>
    </div>
@else
    <p class="text-muted">You have not submitted a proposal yet.</p>
    @if($team->leader_id == auth()->id())
        <a href="{{ route('proposal.create') }}" class="btn btn-primary btn-sm">Submit Proposal</a>
    @else
        <p class="small text-muted">Only your team leader can submit the proposal.</p>
    @endif
@endif

@endsection
