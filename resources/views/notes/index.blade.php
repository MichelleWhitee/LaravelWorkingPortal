@extends('layouts.app')

@section('content')
<div class="container">
    <p class="fs-3" style="text-align: center; font-weight: bolder; margin: 20px; color: #303645">Мои заметки</p>
    <form action="{{ route('notes.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="form-group">
            <label style="font-weight: bold; margin-bottom: 10px;" for="content">Добавить новую заметку</label>
            <textarea style="resize: none; height: 150px;" name="content" id="content" rows="3" class="shadow form-control note-text" placeholder="Введите текст заметки"></textarea>
        </div>
        <button type="submit" style="margin-bottom: 30px;" class="shadow btn btn-add-nt btn-primary mt-3">Добавить</button>
    </form>

    <div class="row">
        @forelse ($notes as $note)
            <div class="col-md-4 mb-4">
                <div class="shadow card">
                    <div class="card-body">
                        <p class="card-text">{{ $note->content }}</p>
                        <small class="text-muted">Создана: {{ $note->created_at->format('d.m.Y H:i') }}</small>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <!-- Кнопка редактирования -->
                        <a href="{{ route('notes.edit', $note) }}" class="shadow btn btn-sm btn-edit-note btn-warning">Редактировать</a>
                        
                        <!-- Форма для удаления заметки -->
                        <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Удалить эту заметку?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="shadow btn btn-sm btn-delete-note btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>У вас пока нет заметок.</p>
        @endforelse
    </div>
</div>
@endsection
