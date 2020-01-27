@extends('layouts.ajax')

@section('content')
<div class="col-12">
    <div class="form login-form">
        <form action="{{ route('login') }}" method="post" role="form" class="contactForm">
            @csrf

            <div class="form-group">
                <input id="email" type="email" placeholder="Your Email" class="form-control input-text @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                <div class="validation"> @error('email') <strong>{{ $message }}</strong> @enderror </div>
            </div>
            <div class="form-group">
                <input id="password" type="password" placeholder="Your Password"  class="form-control input-text @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                <div class="validation"> @error('password') <strong>{{ $message }}</strong> @enderror </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="input-btn">{{ __('Login') }}</button>
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
