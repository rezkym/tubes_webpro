@extends('layouts.guest')

@section('title', 'Login')

@section('header')
<i class="fas fa-user-circle fa-2x mb-3 text-primary"></i>
<h3 class="fw-bold text-center">Login</h3>
<p class="text-muted text-center">Sign in to continue</p>
@endsection

@section('content')
<form method="POST" action="{{ route('login') }}" class="login-form">
    @csrf
    
    <div class="mb-4 input-group-custom">
        <div class="input-icon">
            <i class="fas fa-envelope text-muted"></i>
        </div>
        <input type="email" 
               class="form-control form-control-lg @error('email') is-invalid @enderror" 
               name="email" 
               placeholder="Email address"
               value="{{ old('email') }}" 
               required 
               autofocus>
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input type="checkbox" 
                   class="form-check-input" 
                   name="remember" 
                   id="remember">
            <label class="form-check-label user-select-none" for="remember">
                Remember me
            </label>
        </div>
        <a href="#" class="text-primary text-decoration-none">Forgot password?</a>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
        <i class="fas fa-sign-in-alt me-2"></i>Sign In
    </button>

    <div class="text-center">
        <p class="text-muted">Don't have an account? 
            <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">
                Create one
            </a>
        </p>
    </div>
</form>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.login-form {
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