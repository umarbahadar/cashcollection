<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test Admin',
        //     'email' => 'admin@example.com',
        //     'role_id' => 1, // Assuming Admin role has ID 1
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(AgentSeeder::class);
        $this->call(AccountSeeder::class);


    }
}
