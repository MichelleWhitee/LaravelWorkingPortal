@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4 fs-3" style="color: #383F51; font-weight: bolder">Редактировать заметку</h1>
        
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('notes.index') }}" class="shadow btn-back btn">Назад</a>
        </div>

        <form style="color: #383F51 !important" class="shadow p-4 rounded bg-light" method="POST" action="{{ route('notes.update', $note) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="content">Текст заметки</label>
                <textarea style="resize: none; height: 150px;" name="content" id="content" class="form-control" required>{{ old('content', $note->content) }}</textarea>
            </div>

            <button type="submit" class="shadow btn btn-update mt-3">Сохранить</button>
        </form>
    </div>
@endsection
