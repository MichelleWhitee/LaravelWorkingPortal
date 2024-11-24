<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Убедимся, что в базе есть пользователи для авторов и исполнителей
        if (User::count() == 0) {
            User::factory()->count(10)->create(); // создаем 10 тестовых пользователей
        }

        // Генерируем 50 задач
        Task::factory()->count(50)->create();
    }
}

