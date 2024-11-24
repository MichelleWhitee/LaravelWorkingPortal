@foreach($tasks as $task)
<tr class="task-row">
    <td>{{ $task->id }}</td>
    <td onclick="location.href='{{ route('tasks.edit', $task) }}'" style="cursor: pointer;">{{ $task->title }}</td>
    <td>{{ $task->executor ? $task->executor->name : 'Не назначен' }}</td>
    <td>
        <select class="form-select task-status" data-id="{{ $task->id }}">
            <option value="new" {{ $task->status == 'new' ? 'selected' : '' }}>Новая</option>
            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>В работе</option>
            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Выполнена</option>
        </select>
    </td>
    <td>{{ $task->due_date }}</td>
    <td>
        @if($task->discussions->isNotEmpty())
            <a href="{{ route('discussions.show', $task->discussions->first()->id) }}">
                <img src="{{ asset('storage/imgs/message.svg') }}" alt="Discussion Icon" style="width: 30px; height: 30px;">
            </a>
        @else
            <span>Нет</span>
        @endif
    </td>
    
    @if(auth()->user()->adm == 1)
        <td>
            <button type="button" class="btn btn-danger btn-sm delete-task" data-id="{{ $task->id }}">Удалить</button>
        </td>
    @endif
</tr>
@endforeach