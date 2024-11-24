@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-lg" style="width: 100%; max-width: 500px;">
        <div class="card-header text-center bg-primary text-white" style="background-color: #DDDBF1 !important; color: #383F51 !important">
            <h4 class="fw-bolder">{{ __('Вход в портал') }}</h4>
        </div>

        <div class="card-body" style="border-color: transparent !important; background-color: #DDDBF1 !important">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Пароль') }}</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="form-check-label">{{ __('Запомнить меня') }}</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-update">{{ __('Войти') }}</button>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a class="btn btn-link" href="{{ route('password.request') }}">{{ __('Забыли пароль?') }}</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
