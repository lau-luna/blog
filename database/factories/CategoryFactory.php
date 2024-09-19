<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = \App\Models\Category::class; // Specify the model this factory is for

    public function definition(): array
    {
        $name = $this->faker->unique()->word(20); // Use $this->faker instead of fake()

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}