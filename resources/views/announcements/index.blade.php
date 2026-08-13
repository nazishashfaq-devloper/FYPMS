@extends('layouts.public')

@section('title', 'Announcements')

@section('content')
<h2 class="mb-4"><i class="bi bi-megaphone me-2"></i>Latest Announcements</h2>

@if($announcements->count() > 0)
    @foreach($announcements as $announcement)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $announcement->title }}</h5>
                <p class="card-text">{{ $announcement->message }}</p>
                <small class="text-muted">Published: {{ $announcement->publish_date }}</small>
            </div>
        </div>
    @endforeach
@else
    <p class="text-muted">No announcements have been published yet.</p>
@endif
@endsection
