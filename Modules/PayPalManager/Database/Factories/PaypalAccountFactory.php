<?php

namespace Modules\PayPalManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PayPalManager\App\Models\PaypalAccount;
use Modules\ClientManager\App\Models\Client;

class PaypalAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = PaypalAccount::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'password' => base64_encode('password@123'),
            'seller' => $this->faker->company, 
            'domain_site_fake' => 'http://takepremium.247vn.asia',
            'domain_status' => $this->faker->randomElement(['1', '0']), 
            'max_receive_amount' => 200,
            'active_amount' => 0,
            'hold_amount' => 0,
            'max_order_receive_amount' => 20,
            'proxy' => $this->faker->optional()->ipv4,
            'description' => $this->faker->sentence,
            'payment_method' => $this->faker->randomElement(array_keys(PaypalAccount::getPaymentMethods())),
            'status_id' => $this->faker->numberBetween(1, 5),
            'remover' => $this->faker->name,
            'client_ids' => '1'
        ];
    }
}

