<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class TelegramController
{
    protected $token;

    public function __construct()
    {
        $this->token = env('TELEGRAM_BOT_TOKEN'); // Укажите ваш токен Telegram-бота в .env
    }

    public function sendMessage($chatId, $message)
    {
        if (!$chatId) {
            return false; // Не отправлять сообщение, если chat_id отсутствует
        }

        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        $response = Http::post($url, [
            'chat_id' => $chatId,
            'text' => $message,
        ]);

        return $response->successful();
    }
}
