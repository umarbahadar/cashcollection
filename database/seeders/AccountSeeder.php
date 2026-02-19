<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $accountTypes = [
            ['name' => 'Cash In Hand'],
            ['name' => 'Account Payable'],
          
        ];

        foreach ($accountTypes as $type) {
            $existingType = DB::table('accounts')
                ->where('name', $type['name'])
                ->first();

            if (!$existingType) {
                DB::table('accounts')->insert([
                    'name' => $type['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
