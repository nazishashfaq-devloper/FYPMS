@extends('layouts.dashboard')

@section('title', 'Milestones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Milestone Deadlines</h4>
    <a href="{{ route('admin.milestones.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Milestone
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Team</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($milestones as $m)
                    <tr>
                        <td>{{ $m->title }}</td>
                        <td>{{ $m->team->team_name ?? 'N/A' }}</td>
                        <td>{{ $m->due_date ? \Illuminate\Support\Carbon::parse($m->due_date)->format('d M Y') : '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $m->status == 'completed' ? 'success' : 'secondary' }}">
                                {{ ucfirst($m->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No milestones defined yet.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
