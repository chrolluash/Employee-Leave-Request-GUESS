@extends('layouts.app')

@section('title', 'New Leave Request')

@section('content')
<div class="page-header">
    <h1>Create Leave Request</h1>
    <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">
        ← Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('employee.leave.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="leave_type">Leave Type *</label>
                <select id="leave_type" name="leave_type" required>
                    <option value="">Select Leave Type</option>
                    <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>
                        Sick Leave
                    </option>
                    <option value="vacation" {{ old('leave_type') == 'vacation' ? 'selected' : '' }}>
                        Vacation Leave
                    </option>
                    <option value="personal" {{ old('leave_type') == 'personal' ? 'selected' : '' }}>
                        Personal Leave
                    </option>
                    <option value="emergency" {{ old('leave_type') == 'emergency' ? 'selected' : '' }}>
                        Emergency Leave
                    </option>
                    <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>
                        Maternity Leave
                    </option>
                    <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>
                        Paternity Leave
                    </option>
                </select>
                @error('leave_type')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="start_date">Start Date *</label>
                    <input type="date" id="start_date" name="start_date" 
                           value="{{ old('start_date') }}" 
                           min="{{ date('Y-m-d') }}" required>
                    @error('start_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">End Date *</label>
                    <input type="date" id="end_date" name="end_date" 
                           value="{{ old('end_date') }}" 
                           min="{{ date('Y-m-d') }}" required>
                    @error('end_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="reason">Reason for Leave *</label>
                <textarea id="reason" name="reason" rows="5" 
                          placeholder="Please provide a detailed reason for your leave request (minimum 10 characters)"
                          required>{{ old('reason') }}</textarea>
                <small>Minimum 10 characters, maximum 500 characters</small>
                @error('reason')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit Request</button>
                <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection