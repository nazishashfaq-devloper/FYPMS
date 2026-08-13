@extends('layouts.dashboard')

@section('title', 'Project Domains')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Project Domains / Categories</h4>
    <a href="{{ route('admin.domains.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Domain
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($domains as $domain)
                    <tr>
                        <td class="fw-semibold">{{ $domain->name }}</td>
                        <td class="text-muted">{{ $domain->description }}</td>
                        <td class="text-end">
                            <form action="{{ route('admin.domains.delete', $domain->id) }}" method="POST"
                                  onsubmit="return confirm('Remove this domain?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No domains added yet (e.g. Web Development, AI, Data Science, Networking, Mobile Apps).</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
