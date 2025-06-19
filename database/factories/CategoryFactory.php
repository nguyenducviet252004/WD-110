<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'description' => $this->faker->sentence(),
            'status'      => $this->faker->boolean(90),
            'is_active'   => $this->faker->boolean(95),
        ];
    }

    public function inactive()
    {
        return $this->state([
            'is_active' => false,
        ]);
    }
}
