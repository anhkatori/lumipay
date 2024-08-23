<?php

namespace Modules\StripeManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StripeAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\StripeManager\App\Models\StripeAccount::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'domain' => $this->faker->domainName,
            'max_receive_amount' => $this->faker->randomFloat(2, 1, 10000),
            'current_amount' => $this->faker->randomFloat(2, 0, 10000),
            'max_order_receive_amount' => $this->faker->randomFloat(2, 1, 5000),
            'status' => $this->faker->boolean ? 1 : 0, // Randomly assign 0 or 1
        ];
    }
}

