<?php

namespace Modules\StripeManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\StripeManager\App\Models\StripeAccount;
class StripeManagerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        StripeAccount::factory()->count(20)->create();
    }
}
