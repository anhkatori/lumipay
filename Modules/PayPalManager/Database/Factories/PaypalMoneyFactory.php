<?php

namespace Modules\PayPalManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PayPalManager\App\Models\PaypalAccount;
use Modules\ClientManager\App\Models\Client;

class PaypalMoneyFactory extends Factory
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
        ];
    }
}

