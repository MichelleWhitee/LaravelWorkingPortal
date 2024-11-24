@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4 fs-3" style="color: #383F51; font-weight: bolder">Редактировать информацию о сотруднике</h1>

        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('employees.index') }}" class="shadow btn-back btn">Назад</a>
        </div>

        <!-- Ошибки -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Форма редактирования сотрудника -->
        <form method="POST" action="{{ route('employees.update', $employee) }}" style="color: #383F51 !important" class="shadow p-4 rounded bg-light">
            @csrf
            @method('PUT')

            <!-- Поле "Имя" -->
            <div class="form-group mb-3">
                <label for="name" class="form-label">Имя</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $employee->name) }}" required placeholder="Введите имя">
            </div>

            <!-- Поле "Email" -->
            <div class="form-group mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $employee->email) }}" required placeholder="Введите email">
            </div>

            <!-- Кнопки "Сохранить изменения" и "Назад" -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-update shadow">Сохранить изменения</button>
            </div>
        </form>
    </div>
@endsection
