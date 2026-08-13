@extends('layouts.dashboard')

@section('title', 'Messages')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Conversations</div>
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Leader</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teams as $team)
                    <tr>
                        <td>{{ $team->team_name }}</td>
                        <td>{{ $team->leader->name ?? 'N/A' }}</td>
                        <td class="text-end">
                            <a href="{{ route('supervisor.messages.thread', $team->id) }}" class="btn btn-sm btn-primary">Open Chat</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No teams assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
