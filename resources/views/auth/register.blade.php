@extends('layouts.ajax')

@section('content')
<div class="col-12">
    <div class="form register-form">
        <form action="{{ route('register') }}" method="post" role="form" class="contactForm">
            @csrf
            <input type="hidden" name="will" value="register" />
            <div class="form-group">
                <input id="name" type="text" placeholder="{{ __('Name') }}" class="form-control input-text @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                <div class="validation"> @error('name') <strong>{{ $message }}</strong> @enderror </div>
            </div>
            <div class="form-group">
                <input id="email" type="email" placeholder="{{ __('E-Mail Address') }}" class="form-control input-text @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                <div class="validation"> @error('email') <strong>{{ $message }}</strong> @enderror </div>
            </div>
            <div class="form-group">
                <input id="password" type="password" placeholder="{{ __('Password') }}"  class="form-control input-text @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                <div class="validation"> @error('password') <strong>{{ $message }}</strong> @enderror </div>
            </div>
            <div class="form-group">
                <input id="password-confirm" type="password" placeholder="{{ __('Confirm Password') }}"  class="form-control input-text @error('password-confirm') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                <div class="validation"> @error('password-confirm') <strong>{{ $message }}</strong> @enderror </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_company" id="is_company" {{ old('is_company') ? 'checked' : '' }}>

                    <label class="form-check-label" for="is_company">
                        {{ __('I am a company') }}
                    </label>
                </div>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="input-btn">{{ __('Register') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection