@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="page-header">
    <h1>Edit Employee: {{ $user->name }}</h1>
    <a href="{{ route('admin.employees') }}" class="btn btn-secondary">
        ← Back to Employees
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.employees.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" 
                       value="{{ old('name', $user->name) }}" required autofocus>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" 
                       value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="department">Department *</label>
                    <input type="text" id="department" name="department" 
                           value="{{ old('department', $user->department) }}" required>
                    @error('department')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="position">Position *</label>
                    <input type="text" id="position" name="position" 
                           value="{{ old('position', $user->position) }}" required>
                    @error('position')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <hr>
            <h3>Change Password (Optional)</h3>
            <p style="color: #64748b; margin-bottom: 15px;">Leave blank to keep current password</p>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password">
                    <small>Minimum 8 characters</small>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" 
                           name="password_confirmation">
                    @error('password_confirmation')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Employee</button>
                <a href="{{ route('admin.employees') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection