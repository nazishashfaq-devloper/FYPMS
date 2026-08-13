@extends('layouts.dashboard')

@section('title', 'Submit Proposal')

@section('content')
<div class="card shadow-sm" style="max-width:720px;">
    <div class="card-body">
        <h5 class="card-title mb-1">Submit Project Proposal</h5>
        <p class="text-muted small mb-4">Team: <strong>{{ $team->team_name }}</strong></p>

        <form method="POST" action="{{ route('proposal.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Project Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Domain</label>
                @if($domains->count() > 0)
                    <select name="domain" class="form-select" required>
                        <option value="">-- Select Domain --</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->name }}" {{ old('domain') == $domain->name ? 'selected' : '' }}>{{ $domain->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="text" name="domain" class="form-control" placeholder="e.g. Web Development" value="{{ old('domain') }}" required>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Tools / Technologies</label>
                <input type="text" name="tools" class="form-control" placeholder="e.g. Laravel, MySQL, Bootstrap" value="{{ old('tools') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Abstract / Description</label>
                <textarea name="description" rows="6" class="form-control" required>{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Proposal</button>
            <a href="{{ route('proposal.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
