@extends('layouts.public')

@section('title', 'Deadlines')

@section('content')
<h2 class="mb-4"><i class="bi bi-calendar-event me-2"></i>Upcoming Deadlines</h2>

@if($deadlines->count() > 0)
    @foreach($deadlines as $deadline)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $deadline->title }}</h5>
                <p class="card-text">{{ $deadline->description }}</p>
                <span class="badge bg-danger">Deadline: {{ \Illuminate\Support\Carbon::parse($deadline->deadline_date)->format('d M Y') }}</span>
            </div>
        </div>
    @endforeach
@else
    <p class="text-muted">No deadlines have been published yet.</p>
@endif
@endsection
