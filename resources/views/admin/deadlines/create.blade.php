@extends('layouts.dashboard')

@section('title', 'New Deadline')

@section('content')
<div class="card shadow-sm" style="max-width:640px;">
    <div class="card-body">
        <h5 class="card-title mb-4">Add New Deadline</h5>

        <form method="POST" action="{{ route('admin.deadlines.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Applies To</label>
                <select name="document_type" class="form-select">
                    <option value="">General (applies to every document type)</option>
                    @foreach($documentTypes as $type)
                        <option value="{{ $type }}" {{ old('document_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Leave as "General" to set a single deadline for every document type that doesn't have its own deadline.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Deadline Date</label>
                <input type="date" name="deadline_date" class="form-control" value="{{ old('deadline_date') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Deadline</button>
            <a href="{{ route('admin.deadlines.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
