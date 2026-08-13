@extends('layouts.dashboard')

@section('title', 'Deadlines')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Manage Deadlines</h4>
    <a href="{{ route('admin.deadlines.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Deadline
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Applies To</th>
                    <th>Description</th>
                    <th>Deadline Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deadlines as $deadline)
                    <tr>
                        <td>{{ $deadline->title }}</td>
                        <td>
                            @if($deadline->document_type)
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$deadline->document_type)) }}</span>
                            @else
                                <span class="badge bg-info text-dark">General</span>
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($deadline->description, 80) }}</td>
                        <td>
                            <span class="badge bg-{{ \Illuminate\Support\Carbon::parse($deadline->deadline_date)->isPast() ? 'secondary' : 'danger' }}">
                                {{ \Illuminate\Support\Carbon::parse($deadline->deadline_date)->format('d M Y') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No deadlines yet.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
