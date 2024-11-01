<?php

namespace Modules\StripeManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ClientManager\App\Models\Client;

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
            'domain' => 'http://takepremium.247vn.asia',
            'max_receive_amount' => 200,
            'current_amount' => 0,
            'max_order_receive_amount' => 20,
            'status' => $this->faker->boolean ? 1 : 0, // Randomly assign 0 or 1
            'client_ids' => '1'
        ];
    }
}

