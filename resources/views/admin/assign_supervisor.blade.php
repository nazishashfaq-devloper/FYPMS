@extends('layouts.dashboard')

@section('title', 'Assign Supervisor')

@section('content')
<div class="card shadow-sm" style="max-width:560px;">
    <div class="card-body">
        <h5 class="card-title mb-4">Assign Supervisor to Team</h5>

        @if($teams->count() == 0)
            <p class="text-muted mb-0">All teams currently have a supervisor assigned.</p>
        @else
            <form method="POST" action="/admin/assign-supervisor">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Team</label>
                    <select name="team_id" class="form-select" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Supervisor</label>
                    <select name="supervisor_id" class="form-select" required>
                        @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Assign Supervisor</button>
            </form>
        @endif
    </div>
</div>
@endsection
