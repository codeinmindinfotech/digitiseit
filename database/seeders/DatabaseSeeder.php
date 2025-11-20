<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::updateOrCreate(
            ['email' => 'clive.connolly@gmail.com'],
            [
                'name' => 'Clive Connolly',
                'password' => bcrypt('CL!V@23456') , // default password
                'company_id' => null, // important
            ]
        );
    }
}
