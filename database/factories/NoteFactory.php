<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'    => $this->faker->sentence(4),
            'body'     => $this->faker->paragraph(),
            'file_url' => null,
        ];
    }
}
