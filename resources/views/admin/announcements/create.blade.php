@extends('layouts.dashboard')

@section('title', 'New Announcement')

@section('content')
<div class="card shadow-sm" style="max-width:640px;">
    <div class="card-body">
        <h5 class="card-title mb-4">Publish New Announcement</h5>

        <form method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="message" rows="4" class="form-control" required>{{ old('message') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Publish Date</label>
                <input type="date" name="publish_date" class="form-control" value="{{ old('publish_date', date('Y-m-d')) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Publish Announcement</button>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
