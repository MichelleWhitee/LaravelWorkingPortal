@extends('layouts.app')

@section('content')
    <div class="mb-2 mt-4 text-center">
        <h2 class="font-semibold fs-3 text-dark">
            {{ __('Профиль') }}
        </h2>
    </div>
    <div style="width: 100%; margin-top: 80px;" class="d-flex justify-content-center">
        <div style="width: 100%;" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="profile-card p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl ms-auto mx-auto d-flex align-items-around justify-content-center">
                    
                    <form method="POST" action="{{ route('profile.updateAvatar') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label style="font-weight: bolder" for="avatar" class="mt-2 block text-sm font-medium text-gray-700">Загрузить аватар</label>
                            <input type="file" class="mt-3" name="avatar" id="avatar" required>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="shadow btn btn-primary btn-update">
                                {{ __('Обновить аватар') }}
                            </button>
                        </div>
                    </form>

                    <div class="avatar">


                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="User Avatar" style="width:150px; height:150px; object-fit: cover; border-width: 3px; border-color: #383F51" class="avatar-img rounded-circle">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 25px; margin-bottom: 50px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Info Section -->
            <div class="profile-card p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="profile-card p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            <!-- Account Deletion Section -->
            <div class="profile-card p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection



