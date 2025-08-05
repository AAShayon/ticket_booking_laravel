<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Route>
 */
class RouteFactory extends Factory
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
            'vehicle_id' => \App\Models\Vehicle::factory(),
            'origin' => $this->faker->city,
            'destination' => $this->faker->city,
            'fare' => $this->faker->randomFloat(2, 500, 2000),
            'fare_per_seat' => $this->faker->randomFloat(2, 200, 800),
            'estimated_travel_time' => $this->faker->numberBetween(4, 10) . ' hours',
            'vehicle_number' => $this->faker->bothify('???-####'),
            'time_of_day' => $this->faker->randomElement(['morning', 'afternoon', 'night']),
            'departure_time' => $this->faker->time(),
        ];
    }
}
