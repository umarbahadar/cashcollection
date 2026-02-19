<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Agent Ali',
                'role_id' => 2,
                'email' => 'agent.ali@gmail.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Agent Sara',
                'role_id' => 2,
                'email' => 'agent.sara@gmail.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Agent Usman',
                'role_id' => 2,
                'email' => 'agent.usman@gmail.com',
                'password' => Hash::make('12345678'),
            ],
        ];

        // Insert users if they don't already exist
        foreach ($users as $user) {
            if (isset($user['email'])) {
                $existingUser = DB::table('users')
                    ->where('email', $user['email'])
                    ->first();

                if (!$existingUser) {
                    DB::table('users')->insert([
                        'name' => $user['name'],
                        'role_id' => $user['role_id'],
                        'email' => $user['email'],
                        'password' => $user['password'],
                    ]);
                }
            }
        }
    }
}
