<section>
    <header class="mb-4">
        <h2 class="h5 font-weight-semibold text-dark">
            {{ __('Информация о профиле') }}
        </h2>
        <p class="mt-1 text-muted">
            {{ __("Обновите информацию о своём аккаунте и email - адрес.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Имя') }}</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @if ($errors->has('name'))
                <div class="text-danger mt-2">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Почта') }}</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @if ($errors->has('email'))
                <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2 text-muted">
                    <p>{{ __('Your email address is unverified.') }}</p>
                    <button form="send-verification" class="btn btn-link p-0">{{ __('Click here to re-send the verification email.') }}</button>
                </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="d-flex align-items-center mt-4">
            <button type="submit" class="shadow btn btn-primary btn-update">{{ __('Сохранить') }}</button>

            @if (session('status') === 'profile-updated')
                <p class="text-success ms-3">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
