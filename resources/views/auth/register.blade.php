@extends('layouts.guest')

@section('title', 'Register')

@section('header')
<i class="fas fa-user-plus fa-2x mb-3 text-primary"></i>
<h3 class="fw-bold text-center">Create Account</h3>
<p class="text-muted text-center">Sign up to get started</p>
@endsection

@section('content')
<form method="POST" action="{{ route('register') }}" class="register-form">
    @csrf

    <div class="mb-4 input-group-custom">
        <div class="input-icon">
            <i class="fas fa-user text-muted"></i>
        </div>
        <input type="text" 
               class="form-control form-control-lg @error('name') is-invalid @enderror" 
               name="name" 
               placeholder="Full Name"
               value="{{ old('name') }}" 
               required 
               autofocus>
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4 input-group-custom">
        <div class="input-icon">
            <i class="fas fa-envelope text-muted"></i>
        </div>
        <input type="email" 
               class="form-control form-control-lg @error('email') is-invalid @enderror" 
               name="email" 
               placeholder="Email Address"
               value="{{ old('email') }}" 
               required>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4 input-group-custom">
        <div class="input-icon">
            <i class="fas fa-lock text-muted"></i>
        </div>
        <input type="password" 
               class="form-control form-control-lg @error('password') is-invalid @enderror" 
               name="password" 
               placeholder="Password"
               required>
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4 input-group-custom">
        <div class="input-icon">
            <i class="fas fa-lock text-muted"></i>
        </div>
        <input type="password" 
               class="form-control form-control-lg" 
               name="password_confirmation" 
               placeholder="Confirm Password"
               required>
    </div>

    <div class="mb-4 input-group-custom">
        <div class="input-icon">
            <i class="fas fa-user-tag text-muted"></i>
        </div>
        <select name="role" 
                class="form-control form-control-lg @error('role') is-invalid @enderror" 
                required>
            <option value="" disabled selected>Select Role</option>
            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
        </select>
        @error('role')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
        <i class="fas fa-user-plus me-2"></i>Register
    </button>

    <div class="text-center">
        <p class="text-muted">Already have an account? 
            <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">
                Sign in
            </a>
        </p>
    </div>
</form>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.register-form {
    font-family: 'Inter', sans-serif;
}

.input-group-custom {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
}

.input-group-custom .form-control {
    padding-left: 45px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.input-group-custom .form-control:focus {
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
}

.btn-primary {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    position: relative;
    overflow: hidden;
}

.btn-primary::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
    );
    transition: 0.5s;
}

.btn-primary:hover::after {
    left: 100%;
}

.form-check-input {
    width: 1.2em;
    height: 1.2em;
    cursor: pointer;
}

.form-check-label {
    cursor: pointer;
    font-size: 0.95rem;
}

</style>
@endsection