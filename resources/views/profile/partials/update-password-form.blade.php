<section>
    <header class="mb-4">
        <h2 class="h5 font-weight-semibold text-dark">
            {{ __('Сменить пароль') }}
        </h2>
        <p class="mt-1 text-muted">
            {{ __('Убедитесь что ваш пароль соответствует требованиям безопасности.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Текущий пароль') }}</label>
            <input type="password" id="update_password_current_password" name="current_password" class="form-control" autocomplete="current-password">
            @if ($errors->updatePassword->has('current_password'))
                <div class="text-danger mt-2">{{ $errors->updatePassword->first('current_password') }}</div>
            @endif
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('Новый пароль') }}</label>
            <input type="password" id="update_password_password" name="password" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->has('password'))
                <div class="text-danger mt-2">{{ $errors->updatePassword->first('password') }}</div>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Подтвердите пароль') }}</label>
            <input type="password" id="update_password_password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->has('password_confirmation'))
                <div class="text-danger mt-2">{{ $errors->updatePassword->first('password_confirmation') }}</div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="d-flex align-items-center mt-4">
            <button type="submit" class="shadow btn btn-primary btn-update">{{ __('Сохранить') }}</button>

            @if (session('status') === 'password-updated')
                <p class="text-success ms-3">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
