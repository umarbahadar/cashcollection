<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'role_id' => 1,
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
            ],
           
        ];
        
        //Checking Record In DB & Then Inseting Record
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
