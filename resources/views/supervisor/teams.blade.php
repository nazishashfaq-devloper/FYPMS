@extends('layouts.dashboard')

@section('title', 'My Teams')

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Team Name</th>
                    <th>Leader</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teams as $team)
                    <tr>
                        <td>{{ $team->team_name }}</td>
                        <td>{{ $team->leader->name ?? $team->leader_id }}</td>
                        <td class="text-end">
                            <a href="{{ route('supervisor.meetings.create', $team->id) }}" class="btn btn-sm btn-outline-primary">Schedule Meeting</a>
                            <a href="{{ route('supervisor.evaluation', $team->id) }}" class="btn btn-sm btn-outline-success">Evaluate</a>
                            <a href="{{ route('supervisor.messages.thread', $team->id) }}" class="btn btn-sm btn-outline-secondary">Message</a>
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
