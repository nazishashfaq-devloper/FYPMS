@extends('layouts.dashboard')

@section('title', 'New Domain')

@section('content')
<div class="card shadow-sm" style="max-width:560px;">
    <div class="card-body">
        <h5 class="card-title mb-4">Add Project Domain / Category</h5>

        <form method="POST" action="{{ route('admin.domains.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Artificial Intelligence" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Domain</button>
            <a href="{{ route('admin.domains.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
