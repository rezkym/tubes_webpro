@extends('layouts.guest')

@section('title', 'Login')

@section('header', 'Sign In')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" 
               value="{{ old('email') }}" 
               required 
               autofocus>
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
        <div class="form-check">
            <input type="checkbox" 
                   class="form-check-input" 
                   name="remember" 
                   id="remember">
            <label class="form-check-label" for="remember">
                Remember me
            </label>
        </div>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
            Sign In
        </button>
    </div>

    <div class="text-center mt-3">
        <p class="mb-0">Don't have an account? 
            <a href="{{ route('register') }}" class="text-decoration-none">Create one</a>
        </p>
    </div>
</form>
@endsection