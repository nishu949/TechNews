@extends('layouts.auth')

@section('title','Login')

@section('content')

<h2 class="mb-4 text-center">
    Login
</h2>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="email"
            class="form-control"
            value="{{ old('email') }}"
            required
            autofocus>

        @error('email')
            <small class="text-danger">
                {{ $message }}
            </small>
        @enderror

    </div>

    <div class="mb-3">

        <label class="form-label">
            Password
        </label>

        <input
            type="password"
            name="password"
            class="form-control"
            required>

        @error('password')
            <small class="text-danger">
                {{ $message }}
            </small>
        @enderror

    </div>

    <div class="form-check mb-3">

        <input
            class="form-check-input"
            type="checkbox"
            name="remember"
            id="remember">

        <label class="form-check-label" for="remember">

            Remember Me

        </label>

    </div>

    <button class="btn btn-primary w-100">

        Login

    </button>

    <div class="mt-3 text-center">

        <a href="{{ route('password.request') }}">

            Forgot Password?

        </a>

    </div>

    <div class="mt-2 text-center">

        Don't have an account?

        <a href="{{ route('register') }}">

            Register

        </a>

    </div>

</form>

@endsection