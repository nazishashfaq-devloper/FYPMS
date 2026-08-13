@extends('layouts.dashboard')

@section('title', 'Messages')

@section('content')
<div class="card shadow-sm" style="max-width:760px;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Conversation with {{ $team->team_name }}</span>
        <a href="{{ route('supervisor.messages.index') }}" class="small">&larr; All conversations</a>
    </div>

    <div class="card-body scroll-panel-lg" style="background:#f9fafb;">
        @forelse($messages as $msg)
            <div class="mb-3 d-flex {{ $msg->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="p-2 px-3 rounded-3 {{ $msg->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-white border' }}" style="max-width:75%;">
                    <div class="small fw-semibold mb-1">{{ $msg->sender->name ?? 'Unknown' }}</div>
                    <div>{{ $msg->body }}</div>
                    <div class="small {{ $msg->sender_id == auth()->id() ? 'text-white-50' : 'text-muted' }} mt-1">
                        {{ $msg->created_at->format('d M, h:i A') }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">No messages yet. Start the conversation below.</p>
        @endforelse
    </div>

    <div class="card-footer bg-white">
        <form method="POST" action="{{ route('supervisor.messages.send', $team->id) }}" class="d-flex gap-2">
            @csrf
            <input type="text" name="body" class="form-control" placeholder="Type your message..." required>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>
@endsection
