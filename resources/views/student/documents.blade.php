@extends('layouts.dashboard')

@section('title', 'Project Documents')

@section('content')

@if(!$team)
    {{-- No team yet: show a friendly, self-contained message instead of
         bouncing the user to another page. This mirrors the pattern used
         on the My Team and Proposal pages. --}}
    <div class="card shadow-sm mx-auto" style="max-width:520px;">
        <div class="card-body text-center">
            <p class="text-muted">You need to be part of a team before you can upload project documents.</p>
            <a href="{{ route('team.create') }}" class="btn btn-primary">Create a Team</a>
        </div>
    </div>
@else

    <h4 class="mb-1">{{ $team->team_name }}</h4>
    <p class="text-muted mb-4">Upload and track your team's project deliverables.</p>

    @php
        $allExpired = collect($deadlineStatuses)->every(fn ($s) => $s['expired']);
    @endphp

    @if($allExpired)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            All document submission deadlines have passed. Uploads are currently closed.
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold"><i class="bi bi-cloud-upload-fill"></i>Upload Document</div>
        <div class="card-body">
            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="row g-3" id="documentUploadForm">
                @csrf

                <div class="col-12 col-md-4">
                    <label class="form-label">Document Type</label>
                    <select name="document_type" id="documentTypeSelect" class="form-select" required {{ $allExpired ? 'disabled' : '' }}>
                        <option value="">Select Type</option>
                        @foreach($deadlineStatuses as $type => $status)
                            <option value="{{ $type }}" data-expired="{{ $status['expired'] ? '1' : '0' }}" {{ $status['expired'] ? 'disabled' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                @if($status['deadline'])
                                    (due {{ $status['deadline']->deadline_date->format('d M Y') }}{{ $status['expired'] ? ' — closed' : '' }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text" id="deadlineHint"></div>
                </div>

                <div class="col-12 col-md-5">
                    <label class="form-label">File (PDF / DOC / DOCX, max 10MB)</label>
                    <input type="file" name="file" id="documentFileInput" class="form-control" required {{ $allExpired ? 'disabled' : '' }}>
                </div>

                <div class="col-12 col-md-3 d-flex align-items-end">
                    <button type="submit" id="documentSubmitBtn" class="btn btn-primary w-100" {{ $allExpired ? 'disabled' : '' }}>Upload</button>
                </div>
            </form>
            <p class="text-muted small mt-2 mb-0">Uploading the same document type again will replace the previous file. Uploads are automatically disabled once a document type's deadline has passed.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold"><i class="bi bi-folder2-open"></i>Uploaded Documents</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Feedback</th>
                            <th>Uploaded</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <td>{{ ucfirst(str_replace('_',' ',$doc->document_type)) }}</td>
                                <td>
                                    <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download me-1"></i>Download
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $doc->status == 'approved' ? 'success' : ($doc->status == 'rejected' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($doc->status) }}
                                    </span>
                                </td>
                                <td>{{ $doc->feedback ?? '—' }}</td>
                                <td>{{ $doc->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No documents uploaded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection

@section('scripts')
<script>
    // Client-side convenience only: keeps the form from being submitted for
    // an expired document type. The authoritative check is always on the
    // server (DocumentController@store), so this can never be relied upon
    // for security/business-rule enforcement.
    (function () {
        var select = document.getElementById('documentTypeSelect');
        var submitBtn = document.getElementById('documentSubmitBtn');
        var hint = document.getElementById('deadlineHint');
        if (!select || !submitBtn) return;

        select.addEventListener('change', function () {
            var opt = select.options[select.selectedIndex];
            var expired = opt && opt.dataset.expired === '1';
            submitBtn.disabled = expired || !select.value;
            hint.textContent = expired ? 'The deadline for this document type has passed.' : '';
        });
    })();
</script>
@endsection
