@extends('layouts.dashboard')

@section('title', 'Meetings')

@section('content')
<h4 class="mb-4">Scheduled Meetings</h4>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Link</th>
                    <th>Agenda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($meetings as $meeting)
                    <tr>
                        <td>{{ $meeting->team->team_name ?? 'N/A' }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</td>
                        <td>{{ $meeting->venue ?: '—' }}</td>
                        <td>
                            @if($meeting->meeting_link)
                                <a href="{{ $meeting->meeting_link }}" target="_blank">Join</a>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $meeting->agenda ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No meetings scheduled yet.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
