@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Создать новое обсуждение</h1>

        <form action="{{ route('discussions.store') }}" method="POST" class="shadow p-4 rounded bg-light">
            @csrf
            
            <!-- Discussion Title -->
            <div class="form-group mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Введите заголовок" required>
            </div>

            <!-- Discussion Description -->
            <div class="form-group mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Введите описание" required></textarea>
            </div>

            <!-- Task Selection -->
            <div class="form-group mb-4">
                <label for="task_id" class="form-label">Выберите задачу</label>
                <select name="task_id" id="task_id" class="form-control" required>
                    <option value="">Выберите задачу</option>
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}" {{ $task->id == $selectedTaskId ? 'selected' : '' }}>
                            {{ $task->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">Создать</button>
        </form>
    </div>
@endsection
