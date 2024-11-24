<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'due_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'author_id' => User::inRandomOrder()->first()->id,  // случайный автор
            'executor_id' => User::inRandomOrder()->first()->id, // случайный исполнитель
            'status' => $this->faker->randomElement(['new', 'in_progress', 'completed']),
        ];
    }
}

