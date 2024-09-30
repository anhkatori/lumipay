<?php

namespace Modules\StripeManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ClientManager\App\Models\Client;

class StripeMoneyFactory extends Factory
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
        ];
    }
}

