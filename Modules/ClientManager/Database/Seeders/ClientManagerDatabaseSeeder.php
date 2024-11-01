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
        $client = Client::factory()->make([
            'private_key' => 'FGhYjUvXLOFYT5lGEd6MYGGvaWUZv3gj2kc5G0Cg',
            'merchant_id' => 'cde326a2-5cc5-4c87-9873-8c7dfe770cc2'
        ]);
        $client->save();
        Client::factory()->count(1)->create();
        $this->call([]);
    }
}
