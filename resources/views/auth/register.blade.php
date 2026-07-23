@extends('layouts.auth')

@section('title','Register')

@section('content')

<h2 class="mb-4 text-center">
    Create Account
</h2>

<form method="POST" action="{{ route('register') }}">
@csrf

<div class="mb-3">

<label class="form-label">

Name

</label>

<input
type="text"
name="name"
class="form-control"
value="{{ old('name') }}"
required>

@error('name')
<small class="text-danger">{{ $message }}</small>
@enderror

</div>

<div class="mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
name="email"
class="form-control"
value="{{ old('email') }}"
required>

@error('email')
<small class="text-danger">{{ $message }}</small>
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
<small class="text-danger">{{ $message }}</small>
@enderror

</div>

<div class="mb-4">

<label class="form-label">

Confirm Password

</label>

<input
type="password"
name="password_confirmation"
class="form-control"
required>

</div>

<button class="btn btn-success w-100">

Register

</button>

<div class="text-center mt-3">

Already have an account?

<a href="{{ route('login') }}">

Login

</a>

</div>

</form>

@endsection