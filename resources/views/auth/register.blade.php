@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1>🏢 Create Account</h1>
        <p>Register as an employee</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" 
                   value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" 
                   value="{{ old('email') }}" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" 
                       value="{{ old('department') }}" required>
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" id="position" name="position" 
                       value="{{ old('position') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" 
                   name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>

    <div class="auth-footer">
        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</div>
@endsection