<?php

namespace Modules\AirwalletManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AirwalletManager\Database\Factories\AirwalletAccountFactoryFactory;
use Modules\AirwalletManager\App\Models\AirwalletAccount;

class AirwalletManagerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AirwalletAccount::factory()->count(20)->create();
    }
}
