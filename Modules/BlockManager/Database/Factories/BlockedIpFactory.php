<?php

namespace Modules\BlockManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlockedIpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\BlockManager\App\Models\BlockedIp::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'ip_ban' => $this->faker->ipv4,
            'sort_ip' => $this->faker->ipv4,
        ];
    }
}

