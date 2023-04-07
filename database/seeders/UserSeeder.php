<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@zeizzu.com',
            'phone' => '01234567910',
            'password' => Hash::make('password'),
            'role_id' => '1',
            'isVerified' => '1',
        ]);
        DB::table('users')->insert([
            'name' => 'Driver',
            'email' => 'driver@zeizzu.com',
            'phone' => '01234567910',
            'password' => Hash::make('password'),
            'role_id' => '2',
            'isVerified' => '1',
        ]);
        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@zeizzu.com',
            'phone' => '01234567910',
            'password' => Hash::make('password'),
            'role_id' => '3',
            'isVerified' => '1',
        ]);
    }
}
