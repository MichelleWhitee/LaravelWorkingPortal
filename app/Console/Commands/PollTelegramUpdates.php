<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class PollTelegramUpdates extends Command
{
    protected $signature = 'telegram:poll';
    protected $description = 'Poll Telegram updates';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Получаем последний обработанный update_id
        $lastUpdateId = 0;

        // Получаем новые обновления с учетом offset
        $updates = Telegram::getUpdates(['offset' => $lastUpdateId + 1]);

        foreach ($updates as $update) {
            if ($update->getMessage()) {
                $chatId = $update->getMessage()->getChat()->getId();
                $text = $update->getMessage()->getText();

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $text,
                ]);

                // Удаляем обработанное обновление
                Telegram::getUpdates(['offset' => $update->getUpdateId() + 1]);
            }
        }
    }
}