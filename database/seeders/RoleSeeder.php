<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'name' => 'Super Admin',
            'guard' => 'web',
        ],
        [
            'name' => 'Rider',
            'guard' => 'web',
        ],
        [
            'name' => 'User',
            'guard' => 'web',
        ]);
    }
}
