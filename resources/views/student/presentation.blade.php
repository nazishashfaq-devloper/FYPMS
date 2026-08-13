@extends('layouts.dashboard')

@section('title', 'Presentation Schedule')

@section('content')

@if(!$team)
    <p class="text-muted">Join or create a team to see your presentation schedule.</p>
@else
    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Your Presentation / Defense Schedule</div>
        <div class="card-body p-0">
            <div class="table-responsive">
<table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
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
                            <td><span class="badge bg-info text-dark">{{ ucfirst(str_replace('_',' ',$p->phase)) }}</span></td>
                            <td>{{ \Illuminate\Support\Carbon::parse($p->presentation_date)->format('d M Y') }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($p->presentation_time)->format('h:i A') }}</td>
                            <td>
                                @if($p->meeting_link)
                                    <a href="{{ $p->meeting_link }}" target="_blank">{{ $p->meeting_link }}</a>
                                @else
                                    {{ $p->venue ?: '—' }}
                                @endif
                            </td>
                            <td>{{ $p->panel_members ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No presentations scheduled yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
</div>
        </div>
    </div>
@endif
@endsection
