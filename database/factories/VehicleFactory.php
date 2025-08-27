<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'operator_id' => \App\Models\Operator::factory(),
            'model_number' => $this->faker->bothify('??-####'),
            'type' => $this->faker->randomElement(['AC', 'non-AC', 'sleeper', 'hyundai', 'scania']),
            'capacity' => $this->faker->numberBetween(30, 50),
        ];
    }
}
