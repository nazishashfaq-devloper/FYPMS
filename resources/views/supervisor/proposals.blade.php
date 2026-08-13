@extends('layouts.dashboard')

@section('title', 'Proposal Review')

@section('content')

@if($proposals->count() > 0)
    @foreach($proposals as $proposal)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1">{{ $proposal->title }}</h5>
                        <p class="text-muted mb-1">Domain: {{ $proposal->domain }} &middot; Team: {{ $proposal->team->team_name ?? 'N/A' }}</p>
                    </div>
                    <span class="badge bg-{{ $proposal->status == 'approved' ? 'success' : ($proposal->status == 'rejected' ? 'danger' : 'secondary') }}">
                        {{ ucfirst($proposal->status) }}
                    </span>
                </div>

                @if($proposal->tools)
                    <p class="mb-1"><strong>Tools:</strong> {{ $proposal->tools }}</p>
                @endif

                <p class="mb-2">{{ $proposal->description }}</p>

                @if($proposal->feedback)
                    <p class="text-muted small mb-3"><strong>Feedback given:</strong> {{ $proposal->feedback }}</p>
                @endif

                @if($proposal->status == 'pending')
                    <div class="d-flex gap-2">
                        <form method="POST" action="/supervisor/proposal/approve/{{ $proposal->id }}">
                            @csrf
                            <button class="btn btn-sm btn-success">Approve</button>
                        </form>

                        <form method="POST" action="/supervisor/proposal/reject/{{ $proposal->id }}" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="feedback" class="form-control form-control-sm" placeholder="Reason for rejection" required>
                            <button class="btn btn-sm btn-danger text-nowrap">Reject</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@else
    <p class="text-muted">No proposals found.</p>
@endif
@endsection
