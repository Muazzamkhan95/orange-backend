<?php
namespace Database\Seeders;

use App\Traits\Uuids;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSettingSeeder extends Seeder
{
    use Uuids;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('business_settings')->insert(
            [
            'id'=> '1',
            'type' => 'company_name',
            'value' => 'Zeizzu',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '2',
                'type' => 'company_email',
                'value' => 'abc@123',
                ]

        );
        DB::table('business_settings')->insert(
            [
                'id'=> '3',
                'type' => 'company_address',
                'value' => 'UAE, Dubai, Sharja',
            ]

        );
        DB::table('business_settings')->insert(
            [
                'id'=> '4',
                'type' => 'company_phone',
                'value' => '+6317584587',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '5',
                'type' => 'system_default_currency',
                'value' => '1',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '6',
                'type' => 'timezone',
                'value' => 'UTC',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '7',
                'type' => 'colors',
                'value' => '{"primary":"#f072d4","secondary":"#ffffff"}',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '8',
                'type' => 'company_copyright_text',
                'value' => 'Copyright@2023',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '9',
                'type' => 'company_web_logo',
                'value' => 'storage/company/1673344711232297176.png',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '10',
                'type' => 'company_footer_logo',
                'value' => 'storage/company/1673344711232297176.png',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '11',
                'type' => 'company_fav_icon',
                'value' => 'storage/company/16777492801917320631.png',
            ]
        );
        DB::table('business_settings')->insert(

            [
                'id'=> '12',
                'type' => 'loader_gif',
                'value' => 'storage/company/16777493052034905448.gif',
            ],
        );
        DB::table('business_settings')->insert(

            [
                'id'=> '13',
                'type' => 'company_mobile_logo',
                'value' => 'storage/company/1673344711232297176.png',
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '14',
                'type' => 'cancellation_fee',
                'value' => 60,
            ]
        );
        DB::table('business_settings')->insert(
            [
                'id'=> '15',
                'type' => 'driver_icon',
                'value' => 'storage/company/1677940247674309259.png',
            ]
        );
        }
    }
