@extends('layouts.dashboard')

@section('title', 'Evaluations')

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
<table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Team</th>
                    <th>Phase</th>
                    <th>Marks</th>
                    <th>Remarks</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $eval)
                    <tr>
                        <td>{{ $eval->team->team_name ?? 'Team #'.$eval->team_id }}</td>
                        <td>{{ ucfirst($eval->phase) }}</td>
                        <td>{{ $eval->marks }}</td>
                        <td>{{ $eval->remarks }}</td>
                        <td>{{ $eval->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No evaluations recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection
