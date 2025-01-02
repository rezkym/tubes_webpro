@extends('layouts.guest')

@section('title', 'Register')

@section('header', 'Create Account')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf
    
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               name="name" 
               value="{{ old('name') }}" 
               required 
               autofocus>
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" 
               value="{{ old('email') }}" 
               required>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" 
               required>
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" 
               class="form-control"
               name="password_confirmation" 
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Register as</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">Select Role</option>
            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
        </select>
        @error('role')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
            Register
        </button>
    </div>

    <div class="text-center mt-3">
        <p class="mb-0">Already have an account? 
            <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a>
        </p>
    </div>
</form>
@endsection