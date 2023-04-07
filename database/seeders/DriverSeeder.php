<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('drivers')->insert([
            'id' => 1,
            'name' => 'Driver',
            'user_id' => 2,
            'email' => 'driver@zeizzu.com',
            'phone' => '01234567910',
        ]);
    }
}
