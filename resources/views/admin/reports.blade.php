@extends('layouts.dashboard')

@section('title', 'Reports & Analytics')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Reports &amp; Analytics</h4>
    <form method="POST" action="{{ route('admin.reports.generate') }}">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Generate New Report
        </button>
    </form>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Previous Reports</div>
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Teams</th>
                    <th>Students</th>
                    <th>Supervisors</th>
                    <th>Proposals</th>
                    <th>Approved</th>
                    <th>Pending</th>
                    <th>Rejected</th>
                    <th>Generated On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->total_teams }}</td>
                        <td>{{ $report->total_students }}</td>
                        <td>{{ $report->total_supervisors }}</td>
                        <td>{{ $report->total_proposals }}</td>
                        <td><span class="badge bg-success">{{ $report->approved_proposals }}</span></td>
                        <td><span class="badge bg-secondary">{{ $report->pending_proposals }}</span></td>
                        <td><span class="badge bg-danger">{{ $report->rejected_proposals }}</span></td>
                        <td>{{ $report->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No reports generated yet. Click "Generate New Report" above.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
