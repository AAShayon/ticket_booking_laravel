<?php

namespace Database\Factories;

use App\Models\OperatorRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperatorRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OperatorRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'operator_name' => $this->faker->company,
            'route_details' => $this->faker->sentence,
            'fare_details' => $this->faker->randomFloat(2, 100, 1000),
            'admin_commission_percentage' => $this->faker->randomFloat(2, 5, 20),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
