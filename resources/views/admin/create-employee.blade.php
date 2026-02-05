@extends('layouts.app')

@section('title', 'Create Employee Account')

@section('content')
<div class="page-header">
    <h1>Create New Employee Account</h1>
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
        <form action="{{ route('admin.employees.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" 
                       value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" 
                       value="{{ old('email') }}" required>
                <small>This will be the employee's username</small>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="department">Department *</label>
                    <select id="department" name="department" required>
                        <option value="">-- Select Department --</option>
                        <option value="Accounting" {{ old('department') == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                        <option value="Creative" {{ old('department') == 'Creative' ? 'selected' : '' }}>Creative</option>
                        <option value="Engineering" {{ old('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        <option value="HR" {{ old('department') == 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="IT" {{ old('department') == 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="Merchandising" {{ old('department') == 'Merchandising' ? 'selected' : '' }}>Merchandising</option>
                        <option value="Planning" {{ old('department') == 'Planning' ? 'selected' : '' }}>Planning</option>
                        <option value="Purchasing" {{ old('department') == 'Purchasing' ? 'selected' : '' }}>Purchasing</option>
                        <option value="Research and Deployment" {{ old('department') == 'Research and Deployment' ? 'selected' : '' }}>Research and Deployment</option>
                        <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="Visual" {{ old('department') == 'Visual' ? 'selected' : '' }}>Visual</option>
                    </select>
                    @error('department')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="position">Position *</label>
                    <input type="text" id="position" name="position" 
                           value="{{ old('position') }}" required>
                    @error('position')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required>
                    <small>Minimum 8 characters</small>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input type="password" id="password_confirmation" 
                           name="password_confirmation" required>
                    @error('password_confirmation')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Create Employee Account
                </button>
                <a href="{{ route('admin.employees') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3 style="margin: 0; display: flex; align-items: center; gap: 10px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Note
        </h3>
    </div>
    <div class="card-body">
        <p>After creating the account, provide the employee with:</p>
        <ul style="margin-left: 20px; margin-top: 10px; line-height: 1.8;">
            <li><strong>Email:</strong> The email address you entered</li>
            <li><strong>Password:</strong> The password you set</li>
            <li><strong>Login URL:</strong> {{ url('/') }}</li>
        </ul>
        <p style="margin-top: 15px;">The employee can login and start submitting leave requests immediately.</p>
    </div>
</div>
@endsection