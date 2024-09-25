<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\ClientManager\Database\Seeders\ClientManagerDatabaseSeeder;
use Modules\OrderManager\Database\Seeders\OrderManagerDatabaseSeeder;
use Modules\PayPalManager\Database\Seeders\PayPalManagerDatabaseSeeder;
use Modules\StripeManager\Database\Seeders\StripeManagerDatabaseSeeder;
use Modules\AirwalletManager\Database\Seeders\AirwalletManagerDatabaseSeeder;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\BlockManager\Database\Seeders\BlockManagerDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClientManagerDatabaseSeeder::class,
            OrderManagerDatabaseSeeder::class,
            PayPalManagerDatabaseSeeder::class,
            StripeManagerDatabaseSeeder::class,
            AirwalletManagerDatabaseSeeder::class,
            AuthDatabaseSeeder::class,
            BlockManagerDatabaseSeeder::class
        ]);   
    }
}
