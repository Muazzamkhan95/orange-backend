<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(MenuPermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BusinessSettingSeeder::class);
        $this->call(DriverSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(StateSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
