@extends('layouts.dashboard')

@section('title', 'Documents Review')

@section('content')
<h4 class="mb-4"><i class="bi bi-file-earmark-check-fill text-primary me-2"></i>Documents Review</h4>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Feedback</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                    <tr>
                        <td>{{ $doc->team->team_name ?? 'Team #'.$doc->team_id }}</td>
                        <td>{{ ucfirst(str_replace('_',' ',$doc->document_type)) }}</td>
                        <td>
                            <span class="badge bg-{{ $doc->status == 'approved' ? 'success' : ($doc->status == 'rejected' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($doc->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        </td>
                        <td>{{ $doc->feedback ?? '—' }}</td>
                        <td class="text-end">
                            @if($doc->status == 'pending')
                                <form method="POST" action="/supervisor/document/approve/{{ $doc->id }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Approve</button>
                                </form>

                                <form method="POST" action="/supervisor/document/reject/{{ $doc->id }}" class="d-inline-flex flex-wrap gap-1 align-items-center reject-form">
                                    @csrf
                                    <input type="text" name="feedback" class="form-control form-control-sm reject-reason" placeholder="Reason" required>
                                    <button class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No documents found.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
