<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $roles = [
            ['name' => 'Admin', 'guard_name' => 'admin'],
            ['name' => 'Agent', 'guard_name' => 'agent'],


        ];

        //Checking Record In DB & Then Inseting Record
        
        foreach ($roles as $roleData) {
            $existingRole = DB::table('roles')
                ->where('name', $roleData['name'])
                ->first();

            if (!$existingRole) {
                DB::table('roles')->insert($roleData);
            }
        }
    }
}
