<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\TaskStatusChanged;
use Telegram\Bot\Api;

class SendTaskStatusNotification
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }

    public function handle(TaskStatusChanged $event)
    {
        $user = $event->task->executor;

        if ($user && $user->chat_id) {
            $message = "Изменён статус задачи: *{$event->task->title}*\n";
            $message .= "{$event->oldStatus} -> {$event->task->status}";

            $this->telegram->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        }
    }
}
