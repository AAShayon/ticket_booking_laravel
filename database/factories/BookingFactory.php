<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'from_station' => $this->faker->city,
            'to_station' => $this->faker->city,
            'journey_date' => $this->faker->date,
            'seat_type' => $this->faker->randomElement(['Economy', 'Business', 'First Class']),
            'number_of_seats' => $this->faker->numberBetween(1, 5),
            'total_fare' => $this->faker->randomFloat(2, 10, 500),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}
