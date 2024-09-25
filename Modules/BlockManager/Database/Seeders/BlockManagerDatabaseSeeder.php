<?php

namespace Modules\BlockManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\BlockManager\App\Models\BlockedIp;
use Modules\BlockManager\App\Models\BlockedEmail;

class BlockManagerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlockedIp::factory()->count(5)->create();
        BlockedEmail::factory()->count(5)->create();
    }
}
