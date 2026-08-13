@extends('layouts.dashboard')

@section('title', 'Schedule Meeting')

@section('content')
<div class="card shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <h5 class="card-title mb-1">Schedule Meeting</h5>
        <p class="text-muted small mb-4">Team: {{ $team->team_name }}</p>

        <form method="POST" action="{{ route('supervisor.meetings.store') }}">
            @csrf
            <input type="hidden" name="team_id" value="{{ $team->id }}">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meeting Date</label>
                    <input type="date" name="meeting_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meeting Time</label>
                    <input type="time" name="meeting_time" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Venue</label>
                <input type="text" name="venue" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Online Meeting Link</label>
                <input type="text" name="meeting_link" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Agenda</label>
                <textarea name="agenda" rows="3" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Schedule Meeting</button>
        </form>
    </div>
</div>
@endsection
