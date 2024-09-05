<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;

class AuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!\App\Models\User::where('name', 'htcdev')->first()){
            \App\Models\User::factory()->create([
                'name' => 'htcdev',
                'email' => 'dev@htc.com',
                'password' => 'Htc123@#'
            ]);
        }   
    }
}
