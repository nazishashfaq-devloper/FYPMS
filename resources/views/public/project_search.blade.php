@extends('layouts.public')

@section('title', 'Browse Projects')

@section('content')
<h2 class="mb-4"><i class="bi bi-search me-2"></i>Browse Approved Projects</h2>

<form method="GET" action="{{ route('public.projects.search') }}" class="row g-2 mb-4">
    <div class="col-md-6">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by title or keyword...">
    </div>
    <div class="col-md-4">
        <select name="domain" class="form-select">
            <option value="">All Domains</option>
            @foreach($domains as $domain)
                <option value="{{ $domain }}" {{ request('domain') == $domain ? 'selected' : '' }}>{{ $domain }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
</form>

@if($projects->count() > 0)
    <div class="row g-3">
        @foreach($projects as $project)
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $project->title }}</h5>
                        <span class="badge bg-info text-dark mb-2">{{ $project->domain }}</span>
                        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($project->description, 160) }}</p>
                        <p class="card-text"><small class="text-muted">Team: {{ $project->team->team_name ?? 'N/A' }}</small></p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $projects->links() }}
    </div>
@else
    <p class="text-muted">No approved projects found matching your search.</p>
@endif
@endsection
