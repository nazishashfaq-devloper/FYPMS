@extends('layouts.dashboard')

@section('title', 'New Milestone')

@section('content')
<div class="card shadow-sm" style="max-width:560px;">
    <div class="card-body">
        <h5 class="card-title mb-4">Define New Milestone</h5>

        <form method="POST" action="{{ route('admin.milestones.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Milestone Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. SRS Submission" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Apply To</label>
                <select name="team_id" class="form-select" required>
                    <option value="all">All Teams</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Milestone</button>
            <a href="{{ route('admin.milestones.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
