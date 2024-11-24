@extends('layouts.app')

@section('content')
    <p class="fs-3" style="text-align: center; font-weight: bolder; margin: 20px; color: #303645">Обсуждения</p>
    

    <ul class="list-group mt-3">
        <a href="{{ route('discussions.create') }}" class="shadow btn btn-primary create-d">Создать тему</a>
        @foreach($discussions as $discussion)
            <li style="margin: 20px;" class="shadow d-flex justify-content-center flex-column disc-item list-group-item">
                <a style="margin-top: 10px; margin-bottom: 10px; border-bottom: 1px solid #ccc; width: 50%; align-self: center; padding: 10px; color: #383F51" href="{{ route('discussions.show', $discussion) }}">{{ $discussion->title }}</a>
                <form action="{{ route('discussions.destroy', $discussion) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="margin-top: 10px; margin-bottom: 10px;" class="shadow btn btn-delete-t btn-danger btn-sm">Удалить</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection