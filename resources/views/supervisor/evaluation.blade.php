@extends('layouts.dashboard')

@section('title', 'Evaluate Team')

@section('content')
<div class="card shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <h5 class="card-title mb-1">Team Evaluation</h5>
        <p class="text-muted small mb-4">Team: {{ $team->team_name }}</p>

        <form method="POST" action="/supervisor/evaluation/store">
            @csrf
            <input type="hidden" name="team_id" value="{{ $team->id }}">

            <div class="mb-3">
                <label class="form-label">Marks (0-100)</label>
                <input type="number" name="marks" class="form-control" min="0" max="100" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" rows="4" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Phase</label>
                <select name="phase" class="form-select">
                    <option value="proposal">Proposal Defense</option>
                    <option value="progress">Progress Evaluation</option>
                    <option value="final">Final Defense</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit Evaluation</button>
        </form>
    </div>
</div>
@endsection
