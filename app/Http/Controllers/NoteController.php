<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NoteController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $notes = Auth::user()->notes; // Получаем заметки текущего пользователя
        return view('notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string']);

        Auth::user()->notes()->create([
            'content' => $request->content,
        ]);

        return redirect()->route('notes.index');
    }

    public function edit(Note $note)
    {
        // Убедитесь, что пользователь имеет доступ к этой заметке
        if (Auth::id() !== $note->user_id) {
            abort(403, 'У вас нет прав для редактирования этой заметки.');
        }

        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        // Убедитесь, что пользователь имеет доступ к этой заметке
        if (Auth::id() !== $note->user_id) {
            abort(403, 'У вас нет прав для редактирования этой заметки.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $note->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('notes.index')->with('success', 'Заметка обновлена.');
    }

    public function destroy(Note $note)
    {
        // Убедитесь, что пользователь имеет доступ к этой заметке
        if (Auth::id() !== $note->user_id) {
            abort(403, 'У вас нет прав для редактирования этой заметки.');
        }

        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Заметка удалена.');
    }

}

