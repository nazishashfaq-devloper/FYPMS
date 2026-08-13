@extends('layouts.dashboard')

@section('title', 'My Milestones')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Milestone Tracking</div>
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($milestones as $milestone)
                    <tr>
                        <td>{{ $milestone->title }}</td>
                        <td>{{ $milestone->due_date ? \Illuminate\Support\Carbon::parse($milestone->due_date)->format('d M Y') : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $milestone->status == 'completed' ? 'success' : 'secondary' }}">
                                {{ ucfirst($milestone->status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No milestones assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
