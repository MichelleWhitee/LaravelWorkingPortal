<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Discussion;
use App\Events\TaskStatusChanged;
use App\Http\Controllers\TelegramController;

use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $telegram;

    public function __construct(TelegramController $telegram)
    {
        $this->telegram = $telegram;
    }

    public function index(Request $request)
    {
        // Получаем фильтр из запроса
        $filter = $request->input('filter', 'active');

        // Получаем текущего аутентифицированного пользователя
        $user = auth()->user();

        // Считаем задачи по категориям, учитывая фильтрацию по пользователю
        if ($user->adm == 1) {
            // Для администраторов показываем все задачи
            $counts = [
                'active' => Task::whereIn('status', ['new', 'in_progress'])->count(),
                'today' => Task::whereDate('due_date', now()->toDateString())->count(),
                'tomorrow' => Task::whereDate('due_date', now()->addDay()->toDateString())->count(),
                'completed' => Task::where('status', 'completed')->count(),
                'all' => Task::count(),
            ];
        } else {
            // Для обычных пользователей фильтруем по executor_id
            $counts = [
                'active' => Task::whereIn('status', ['new', 'in_progress'])->where('executor_id', $user->id)->count(),
                'today' => Task::whereDate('due_date', now()->toDateString())->where('executor_id', $user->id)->count(),
                'tomorrow' => Task::whereDate('due_date', now()->addDay()->toDateString())->where('executor_id', $user->id)->count(),
                'completed' => Task::where('status', 'completed')->where('executor_id', $user->id)->count(),
                'all' => Task::where('executor_id', $user->id)->count(),
            ];
        }

        // Получаем задачи в зависимости от фильтра
        switch ($filter) {
            case 'today':
                $tasks = $user->adm == 1
                    ? Task::whereDate('due_date', now()->toDateString())->get()
                    : Task::whereDate('due_date', now()->toDateString())->where('executor_id', $user->id)->get();
                break;
            case 'tomorrow':
                $tasks = $user->adm == 1
                    ? Task::whereDate('due_date', now()->addDay()->toDateString())->get()
                    : Task::whereDate('due_date', now()->addDay()->toDateString())->where('executor_id', $user->id)->get();
                break;
            case 'completed':
                $tasks = $user->adm == 1
                    ? Task::where('status', 'completed')->get()
                    : Task::where('status', 'completed')->where('executor_id', $user->id)->get();
                break;
            case 'all':
                $tasks = $user->adm == 1
                    ? Task::all()
                    : Task::where('executor_id', $user->id)->get();
                break;
            default:
                $tasks = $user->adm == 1
                    ? Task::whereIn('status', ['new', 'in_progress'])->get()
                    : Task::whereIn('status', ['new', 'in_progress'])->where('executor_id', $user->id)->get();
                break;
        }

        if ($request->ajax()) {
            return view('tasks.task_list', compact('tasks')); // Возвращаем только список задач
        }

        // Передаем задачи и количество задач по фильтрам в представление
        $response = response()->view('tasks.index', compact('tasks', 'counts', 'filter'));
        $response->header('Cache-Control', 'no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        return $response;
    }

    public function create(){
        $users = User::all();
        return view('tasks.edit', compact('users'));
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'due_date' => 'nullable|date',
            'executor_id' => 'nullable|exists:users,id',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'author_id' => auth()->id(),
            'executor_id' => $request->executor_id ?? auth()->id(), // Если не указан исполнитель, ставим автором
            'status' => 'new',
        ]);

         // Отправляем сообщение в Telegram
         $executor = User::find($request->executor_id);
         $author = User::find($task->author_id);

         if ($task->executor_id && $executor->chat_id) {
            $message = "Новая задача \"{$task->title}\" от {$author->name} \n\n {$task->description}";
            $this->telegram->sendMessage($executor->chat_id, $message);
        }

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task)
    {
        $users = User::all();
        $discussion = Discussion::where('task_id', $task->id)->first(); // Поиск обсуждения по задаче
    
        return view('tasks.edit', compact('task', 'users', 'discussion'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'due_date' => 'nullable|date',
            'executor_id' => 'nullable|exists:users,id',
            'status' => 'required|in:new,in_progress,completed',
        ]);
        
        $oldStatus = $task->status;
        $task->update($request->all());

        if ($oldStatus !== $task->status) {
            event(new TaskStatusChanged($task, $oldStatus));
        }

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        // Проверка на права администратора
        if (auth()->user()->adm != 1) {
            abort(403); // Для обычных пользователей доступ закрыт
        }

        // Удаляем задачу
        $task->delete();

        // Если пользователь администратор, то считаем все задачи
        if (auth()->user()->adm == 1) {
            $counts = [
                'active' => Task::whereIn('status', ['new', 'in_progress'])->count(),
                'today' => Task::whereDate('due_date', now()->toDateString())->count(),
                'tomorrow' => Task::whereDate('due_date', now()->addDay()->toDateString())->count(),
                'completed' => Task::where('status', 'completed')->count(),
                'all' => Task::count(),
            ];
        } else {
            // Для обычных пользователей считаем только их задачи
            $counts = [
                'active' => Task::whereIn('status', ['new', 'in_progress'])->where('executor_id', auth()->id())->count(),
                'today' => Task::whereDate('due_date', now()->toDateString())->where('executor_id', auth()->id())->count(),
                'tomorrow' => Task::whereDate('due_date', now()->addDay()->toDateString())->where('executor_id', auth()->id())->count(),
                'completed' => Task::where('status', 'completed')->where('executor_id', auth()->id())->count(),
                'all' => Task::where('executor_id', auth()->id())->count(),
            ];
        }

        // Проверка для AJAX-запроса
        if (request()->ajax()) {
            return response()->json(['success' => true, 'counts' => $counts]); // Возвращаем JSON-ответ для AJAX
        }

        // Возвращаем на страницу задач
        return redirect()->route('tasks.index');
    }


    public function updateStatus(Request $request, Task $task)
    {   
        $oldStatus = $task->status;
        
        $task->update(['status' => $request->status]);
         // Отправляем сообщение в Telegram
        if ($task->executor && $task->executor->chat_id) {
            $message = "Изменение статуса задачи \"{$task->title}\" от {$task->author->name} \n\n {$oldStatus} --> {$task->status}";
            $this->telegram->sendMessage($task->executor->chat_id, $message);
        }

        // Если пользователь администратор, то считаем все задачи
        if (auth()->user()->adm == 1) {
            $counts = [
                'active' => Task::whereIn('status', ['new', 'in_progress'])->count(),
                'today' => Task::whereDate('due_date', now()->toDateString())->count(),
                'tomorrow' => Task::whereDate('due_date', now()->addDay()->toDateString())->count(),
                'completed' => Task::where('status', 'completed')->count(),
                'all' => Task::count(),
            ];
        } else {
            // Для обычных пользователей считаем только их задачи
            $counts = [
                'active' => Task::whereIn('status', ['new', 'in_progress'])->where('executor_id', auth()->id())->count(),
                'today' => Task::whereDate('due_date', now()->toDateString())->where('executor_id', auth()->id())->count(),
                'tomorrow' => Task::whereDate('due_date', now()->addDay()->toDateString())->where('executor_id', auth()->id())->count(),
                'completed' => Task::where('status', 'completed')->where('executor_id', auth()->id())->count(),
                'all' => Task::where('executor_id', auth()->id())->count(),
            ];
        }

        return response()->json(['counts' => $counts]);
    }
}
