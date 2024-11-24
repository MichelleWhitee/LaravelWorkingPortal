<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Отображение списка обсуждений
    public function index()
    {
        $discussions = Discussion::latest()->with('user')->get();
        return view('discussions.index', compact('discussions'));
    }

    // Форма для создания нового обсуждения
    // DiscussionController.php

    public function create(Request $request)
    {
        $user = auth()->user();
        $tasks = $user->adm == 1 ? Task::all() : $user->tasks;

        return view('discussions.create', [
            'tasks' => $tasks,
            'selectedTaskId' => $request->task_id, // передача выбранной задачи
        ]);
    }


    // Сохранение нового обсуждения
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'task_id' => 'required|exists:tasks,id', // Проверка на существующую задачу
        ]);

        $discussion = new Discussion([
            'title' => $request->title,
            'user_id' => auth()->id(),
            'task_id' => $request->task_id, // Сохранение задачи
        ]);
        $discussion->save();

        return redirect()->route('discussions.index')->with('success', 'Тема обсуждения создана');
    }

    // Просмотр конкретного обсуждения и его сообщений
    public function show(Discussion $discussion)
    {
        $discussion->load('messages.user'); // Загружаем сообщения с пользователями
        return view('discussions.show', compact('discussion'));
    }

    // Удаление обсуждения
    public function destroy(Discussion $discussion)
    {
        if ($discussion->user_id !== auth()->id()) {
            return redirect()->route('discussions.index')->withErrors('Вы не можете удалить эту тему');
        }

        $discussion->delete();

        return redirect()->route('discussions.index')->with('success', 'Тема обсуждения удалена');
    }
}