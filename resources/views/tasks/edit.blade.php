@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4 fs-3" style="color: #383F51; font-weight: bolder">{{ isset($task) ? 'Редактировать' : 'Создать' }} задачу</h1>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('tasks.index') }}" class="shadow btn-back btn">Назад</a>
            
            <!-- Button for creating or navigating to discussion -->
            @if (isset($discussion))
                <a href="{{ route('discussions.show', $discussion->id) }}" class="shadow btn btn-back">Перейти к обсуждению</a>
            @elseif (isset($task))
                <a href="{{ route('discussions.create', ['task_id' => $task->id]) }}" class="shadow btn btn-back">Создать обсуждение</a>
            @endif
        </div>

        <!-- Task Form -->
        <form method="POST" style="color: #383F51 !important" action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}" class="shadow p-4 rounded bg-light">
            @csrf
            @if(isset($task))
                @method('PUT')
            @endif

            <!-- Task Title -->
            <div class="form-group mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $task->title ?? '') }}" required>
            </div>

            <!-- Task Description -->
            <div class="form-group mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea style="resize: none" name="description" id="description" class="form-control" rows="4" required>{{ old('description', $task->description ?? '') }}</textarea>
            </div>

            <div class="container d-flex justify-content-center">
                    <!-- Due Date -->
                <div class="task-edit-s form-group mb-3" style="width: 12% !important">
                    <label for="due_date" class="form-label">Срок</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $task->due_date ?? '') }}">
                </div>

                @php
                    $statusLabels = [
                        'new' => 'Новая',
                        'in_progress' => 'В работе',
                        'completed' => 'Выполнена',
                    ];
                @endphp

                <!-- Task Status -->
                <div {{ isset($task) ? '' : 'hidden' }} class="task-edit-s form-group mb-3" style="width: 12% !important">
                    <label for="status" class="form-label">Статус</label>
                    <select name="status" id="status" class="form-control">
                        @foreach($statusLabels as $status => $label)
                            <option value="{{ $status }}" {{ old('status', $task->status ?? '') == $status ? 'selected' : '' }}>
                                {{ ucfirst($label) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Task Executor -->
                <div class="task-edit-s form-group mb-4" style="width: 20% !important">
                    <label for="executor_id" class="form-label">Исполнитель</label>
                    <select name="executor_id" id="executor_id" class="form-control">
                        <option value="">Не назначен</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('executor_id', $task->executor_id ?? '') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="margin-top: 30px; margin-bottom: 10px;" class="container d-flex">
                <button style="width: 12%; align-self: flex-start" type="submit" class="shadow btn-update btn btn-primary center">{{ isset($task) ? 'Сохранить' : 'Создать' }}</button>
            </div>
        </form>
    </div>
@endsection
