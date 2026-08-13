@extends('layouts.dashboard')

@section('title', 'Add User')

@section('content')
<div class="card shadow-sm" style="max-width:560px;">
    <div class="card-body">
        <h5 class="card-title mb-4">Create New User</h5>

        <form method="POST" action="/admin/users/store">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="student">Student</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="/admin/users" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
