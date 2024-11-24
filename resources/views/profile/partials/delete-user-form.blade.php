<section class="space-y-6">
    <header>
        <h2 class="h5 font-weight-semibold text-dark">
            {{ __('Удалить аккаунт') }}
        </h2>
        <p class="mt-1 text-muted">
            {{ __('При удалении аккаунта все данные будут безвозвратно удалены.') }}
        </p>
    </header>

    <button class="shadow btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">{{ __('Удалить аккаунт') }}</button>

    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
                @csrf
                @method('delete')

                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUserDeletionLabel">{{ __('Are you sure you want to delete your account?') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>
                    <input type="password" name="password" class="form-control" placeholder="{{ __('Password') }}">
                    @if ($errors->userDeletion->has('password'))
                        <div class="text-danger mt-2">{{ $errors->userDeletion->first('password') }}</div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>
