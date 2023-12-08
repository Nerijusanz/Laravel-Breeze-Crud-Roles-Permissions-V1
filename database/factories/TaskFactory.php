<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Task;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{

    protected $model = Task::class;


    public function definition(): array
    {
        return [
            'name' => fake()->words(2,true),
            'description' => fake()->sentence(),
        ];
    }
}
