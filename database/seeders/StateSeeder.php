<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert([
            'name' => 'Abu Dubai'
        ]);
        DB::table('states')->insert([
            'name' => 'Dubai'
        ]);
        DB::table('states')->insert([
            'name' => 'Sharjah'
        ]);
    }
}
