<?php

namespace Modules\PayPalManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PayPalManager\App\Models\PaypalAccountStatus;
use Modules\PayPalManager\App\Models\PaypalAccount;

class PayPalManagerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalStatus = PaypalAccountStatus::count();
        if(!$totalStatus){
            PaypalAccountStatus::insert([
                ['name' => 'Work'],
                ['name' => 'Pending'],
                ['name' => 'Limited 180d'],
                ['name' => 'Limited Step'],
                ['name' => 'Sent withdraw 180d'],
                ['name' => 'Bank'],
            ]);
        }

        PaypalAccount::factory()->count(5)->create();
    }
}
