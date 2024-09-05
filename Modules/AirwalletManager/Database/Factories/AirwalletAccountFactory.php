<?php

namespace Modules\AirwalletManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ClientManager\App\Models\Client;

class AirwalletAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\AirwalletManager\App\Models\AirwalletAccount::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'domain' => $this->faker->domainName,
            'max_receive_amount' => $this->faker->randomFloat(2, 1, 10000),
            'current_amount' => $this->faker->randomFloat(2, 0, 10000),
            'max_order_receive_amount' => $this->faker->randomFloat(2, 1, 5000),
            'status' => $this->faker->boolean ? 1 : 0,
            'client_id' => Client::factory(),
        ];
    }
}

