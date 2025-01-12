<?php

namespace Modules\ClientManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ClientManager\App\Models\Client;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'username' => $this->faker->unique()->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'private_key' =>  $this->faker->unique()->uuid,
            'merchant_id' =>  $this->faker->unique()->uuid
        ];
    }
}

