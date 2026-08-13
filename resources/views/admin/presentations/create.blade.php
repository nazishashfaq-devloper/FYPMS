@extends('layouts.dashboard')

@section('title', 'Schedule Presentation')

@section('content')
<div class="card shadow-sm" style="max-width:640px;">
    <div class="card-body">
        <h5 class="card-title mb-4">Schedule Presentation / Defense</h5>

        <form method="POST" action="{{ route('admin.presentations.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Team</label>
                <select name="team_id" class="form-select" required>
                    <option value="">-- Select Team --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Phase</label>
                <select name="phase" class="form-select" required>
                    <option value="proposal_defense">Proposal Defense</option>
                    <option value="progress_evaluation">Progress Evaluation</option>
                    <option value="final_defense">Final Defense</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="presentation_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Time</label>
                    <input type="time" name="presentation_time" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Venue</label>
                <input type="text" name="venue" class="form-control" placeholder="e.g. Room 204 / Online">
            </div>

            <div class="mb-3">
                <label class="form-label">Meeting Link (if online)</label>
                <input type="text" name="meeting_link" class="form-control" placeholder="https://...">
            </div>

            <div class="mb-3">
                <label class="form-label">Panel Members</label>
                <textarea name="panel_members" rows="2" class="form-control" placeholder="Names of evaluation panel members"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Schedule Presentation</button>
            <a href="{{ route('admin.presentations.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
