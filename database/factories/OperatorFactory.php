<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operator>
 */
class OperatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'contact_email' => $this->faker->unique()->safeEmail,
            'contact_phone' => $this->faker->phoneNumber,
            'admin_commission_percentage' => $this->faker->randomFloat(2, 5, 20),
            'user_id' => \App\Models\User::factory(),
            'nid' => $this->faker->numerify('##########'),
            'address' => $this->faker->address,
            'transport_business_license' => $this->faker->bothify('TBL-#######'),
        ];
    }
}
