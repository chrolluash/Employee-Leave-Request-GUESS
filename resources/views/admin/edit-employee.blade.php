@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="page-header">
    <h1>Edit Employee: {{ $user->name }}</h1>
    <a href="{{ route('admin.employees') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Back to Employees
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
                    <select id="department" name="department" required>
                        <option value="">-- Select Department --</option>
                        <option value="Accounting" {{ old('department', $user->department) == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                        <option value="Creative" {{ old('department', $user->department) == 'Creative' ? 'selected' : '' }}>Creative</option>
                        <option value="Engineering" {{ old('department', $user->department) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        <option value="HR" {{ old('department', $user->department) == 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="IT" {{ old('department', $user->department) == 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="Merchandising" {{ old('department', $user->department) == 'Merchandising' ? 'selected' : '' }}>Merchandising</option>
                        <option value="Planning" {{ old('department', $user->department) == 'Planning' ? 'selected' : '' }}>Planning</option>
                        <option value="Purchasing" {{ old('department', $user->department) == 'Purchasing' ? 'selected' : '' }}>Purchasing</option>
                        <option value="Research and Deployment" {{ old('department', $user->department) == 'Research and Deployment' ? 'selected' : '' }}>Research and Deployment</option>
                        <option value="Sales" {{ old('department', $user->department) == 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="Visual" {{ old('department', $user->department) == 'Visual' ? 'selected' : '' }}>Visual</option>
                    </select>
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
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Update Employee
                </button>
                <a href="{{ route('admin.employees') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
