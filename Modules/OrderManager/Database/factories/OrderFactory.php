<?php
namespace Modules\OrderManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\OrderManager\App\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // 'request_id' => $this->faker->uuid,
            // 'client_id' => $this->faker->randomNumber(4),
            // 'amount' => $this->faker->randomNumber(5),
            // 'email' => $this->faker->email,
            // 'ip' => $this->faker->ipv4,
            // 'description' => $this->faker->sentence,
            // 'cancel_url' => $this->faker->url,
            // 'return_url' => $this->faker->url,
            // 'notify_url' => $this->faker->url,
            // 'method' => $this->faker->randomElement(['paypal', 'credit cart 1', 'credit cart 2']),
            // 'status' => $this->faker->randomElement(['error', 'processing', 'complete']),
            // 'method_account' => $this->faker->randomNumber(4),
        ];
    }
}