@extends('layouts.dashboard')

@section('title', 'Evaluation History')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Evaluation History</div>
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Phase</th>
                    <th>Marks</th>
                    <th>Remarks</th>
                    <th>Supervisor</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $eval)
                    <tr>
                        <td>{{ ucfirst($eval->phase) }}</td>
                        <td>{{ $eval->marks ?? 'Not graded' }}</td>
                        <td>{{ $eval->remarks ?? 'No remarks' }}</td>
                        <td>{{ $eval->supervisor->name ?? 'N/A' }}</td>
                        <td>{{ $eval->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No evaluation records found.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
