@extends('layouts.dashboard')

@section('title', 'Milestones')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Team Milestones</div>
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Milestone</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($milestones as $m)
                    <tr>
                        <td>{{ $m->team->team_name ?? 'N/A' }}</td>
                        <td>{{ $m->title }}</td>
                        <td>{{ $m->due_date ? \Illuminate\Support\Carbon::parse($m->due_date)->format('d M Y') : '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $m->status == 'completed' ? 'success' : 'secondary' }}">
                                {{ ucfirst($m->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            @if($m->status == 'pending')
                                <form method="POST" action="{{ route('supervisor.milestones.update', $m->id) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button class="btn btn-sm btn-success">Mark Completed</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('supervisor.milestones.update', $m->id) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="pending">
                                    <button class="btn btn-sm btn-outline-secondary">Reopen</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No milestones found for your teams.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
