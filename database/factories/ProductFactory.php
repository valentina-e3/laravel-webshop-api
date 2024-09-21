<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'SKU' => (string) Str::uuid(),
            'name' => $this->faker->word . ' Product',
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 5, 300),
        ];
    }
}
