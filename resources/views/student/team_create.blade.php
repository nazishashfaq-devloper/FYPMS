@extends('layouts.dashboard')

@section('title', 'Create Team')

@section('content')
<div class="card shadow-sm" style="max-width:520px;">
    <div class="card-body">
        <h5 class="card-title mb-4">Create Your Project Team</h5>
        <p class="text-muted small">You'll automatically become the team leader. You can invite up to 2 more members afterwards.</p>

        <form method="POST" action="/team/store">
            @csrf

            <div class="mb-3">
                <label class="form-label">Team Name</label>
                <input type="text" name="team_name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Team</button>
        </form>
    </div>
</div>
@endsection
