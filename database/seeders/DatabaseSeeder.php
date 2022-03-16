<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'koutras@openit.gr',
            'password' => Hash::make('0p3nIT1@Oa'),
            'phone' => '6975987456',
            'role' => 'admin',
            'state' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}
