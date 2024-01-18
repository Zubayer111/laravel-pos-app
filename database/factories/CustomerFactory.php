<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->words(2, true),
            "email" => $this->faker->email(),
            "mobile" => $this->faker->phoneNumber(),
            'user_id' => $this->faker->randomElement([19, 20]),
        ];
    }
}
