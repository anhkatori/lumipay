<?php

namespace Modules\ClientManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ClientManager\App\Models\Client;

class ClientManagerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory()->count(20)->create();
        // $this->call([]);
    }
}
