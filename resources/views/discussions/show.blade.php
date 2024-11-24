@extends('layouts.app')

@section('content')
    <p class="fs-3 mb-3 mt-4 disc-title text-center">{{ $discussion->title }}</p>

    <div class="container chat">
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('discussions.index') }}" class="shadow btn-back btn">Назад</a>
        </div>
        
        <div id="chat-box" class="shadow mb-3 p-3" style="border: 2px solid #ccc; max-height: 600px; overflow-y: auto;">
            
            @foreach($discussion->messages as $message)
                <div class="message {{ $message->user_id === auth()->id() ? 'me' : 'other' }}">
                    <div class="message-content">
                        <strong>{{ $message->user->name }}:</strong>
                        <p>{{ $message->content }}</p>
                    </div>

                    @if($message->user_id === auth()->id())
                        <form  action="{{ route('messages.destroy', $message) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="float: right;" class="mt-2 btn btn-danger btn-sm">Удалить</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <form action="{{ route('messages.store', $discussion) }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="content" class="form-control" placeholder="Введите сообщение..." required>
                <button type="submit" class="shadow btn btn-add-nt">Отправить</button>
            </div>
        </form>
    </div>
@endsection
