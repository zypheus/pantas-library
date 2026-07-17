@extends('layouts.auth')

@section('title', 'Sign in')

@section('content')
    <div class="auth-page__hero">
        <h1>Staff sign in</h1>
        <p>Access the staff portal, catalog, or developer console with your library account.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger auth-page__alert">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="auth-page__field">
            <label for="email">Email address</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="you@school.edu"
                   required
                   autofocus
                   autocomplete="username">
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-page__field">
            <label for="password">Password</label>
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Your password"
                   required
                   autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-page__row">
            <label class="auth-page__remember">
                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="auth-page__link">Forgot password?</a>
        </div>

        <button type="submit" class="auth-page__btn auth-page__btn--primary">Sign in</button>
    </form>

    <div class="auth-page__divider">or</div>

    <a href="{{ route('landing') }}" class="auth-page__btn auth-page__btn--outline">Browse OPAC catalog</a>
    <a href="{{ route('home') }}" class="auth-page__btn auth-page__btn--outline">← Back to home</a>
@endsection
