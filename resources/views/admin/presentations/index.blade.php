@extends('layouts.dashboard')

@section('title', 'Presentations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Presentation / Defense Schedule</h4>
    <a href="{{ route('admin.presentations.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Schedule Presentation
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Phase</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue / Link</th>
                    <th>Panel</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presentations as $p)
                    <tr>
                        <td>{{ $p->team->team_name ?? 'N/A' }}</td>
                        <td><span class="badge bg-info text-dark">{{ ucfirst(str_replace('_',' ',$p->phase)) }}</span></td>
                        <td>{{ \Illuminate\Support\Carbon::parse($p->presentation_date)->format('d M Y') }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($p->presentation_time)->format('h:i A') }}</td>
                        <td>{{ $p->venue ?: $p->meeting_link ?: '—' }}</td>
                        <td>{{ $p->panel_members ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No presentations scheduled yet.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
