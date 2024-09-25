<?php

namespace Modules\BlockManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlockedEmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\BlockManager\App\Models\BlockedEmail::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->name,
            'money_account' => $this->faker->randomFloat(2, 0, 10000),
            'money_bonus' => $this->faker->randomFloat(2, 0, 10000),
            'status_lock' => $this->faker->randomElement(['1', '0']), 
            'status_delete' => $this->faker->randomElement(['1', '0']), 
        ];
    }
}

