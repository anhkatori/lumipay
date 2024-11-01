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
            'domain' => 'http://takepremium.247vn.asia',
            'max_receive_amount' => 200,
            'current_amount' => 0,
            'max_order_receive_amount' => 20,
            'status' => $this->faker->boolean ? 1 : 0,
            'client_ids' => '1,3'
        ];
    }
}

