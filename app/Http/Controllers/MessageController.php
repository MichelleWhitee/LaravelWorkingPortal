<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Сохранение сообщения в обсуждении
    public function store(Request $request, Discussion $discussion)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = new Message([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'discussion_id' => $discussion->id,
        ]);
        $message->save();

        return redirect()->route('discussions.show', $discussion)->with('success', 'Сообщение отправлено');
    }

    // Удаление сообщения
    public function destroy(Message $message)
    {
        if ($message->user_id !== auth()->id()) {
            return redirect()->back()->withErrors('Вы не можете удалить это сообщение');
        }

        $message->delete();

        return redirect()->back()->with('success', 'Сообщение удалено');
    }
}

