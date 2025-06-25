<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DonutApi>
 */
class DonutApiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'seal_of_approval' => $this->faker->numberBetween(1,5),
            'price' => $this->faker->randomFloat(2, 1, 10),
        ];
    }
}
