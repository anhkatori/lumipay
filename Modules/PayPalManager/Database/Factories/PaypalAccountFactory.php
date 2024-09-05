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
            'password' => bcrypt('password'), // Use bcrypt to hash the password
            'domain_site_fake' => $this->faker->domainName,
            'max_receive_amount' => $this->faker->randomFloat(2, 1, 10000),
            'active_amount' => $this->faker->randomFloat(2, 0, 10000),
            'hold_amount' => $this->faker->randomFloat(2, 0, 10000),
            'max_order_receive_amount' => $this->faker->randomFloat(2, 1, 5000),
            'proxy' => $this->faker->optional()->ipv4, // Optional proxy field
            'days_stopped' => $this->faker->numberBetween(0, 30),
            'status_id' => $this->faker->numberBetween(1, 5), // Assumes status IDs are between 1 and 5
            'description' => $this->faker->sentence,
            'payment_method' => $this->faker->randomElement(array_keys(PaypalAccount::getPaymentMethods())), // Random payment method
            'client_id' => Client::factory(), // Assumes you have a factory for Client model
        ];
    }
}

