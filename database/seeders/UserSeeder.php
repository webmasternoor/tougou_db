<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            [
                'mgt_no' => '4090',
                'password' => Hash::make('12345678'),
                'department' => 'SE',
                'furigana' => 'なりた　ともゆき',
                'family_name' => '成田',
                'first_name' => '智之',
                'region' => '東京',
                // 'email' => '',
                // 'email2' => '',
                'official_registration_date' => '11/16/2021',
                // 'birthday' => '',
                // 'sex' => '',
                // 'postal_code' => '',
                // 'current_address' => '',
                // 'desired_subject' => '',
                // 'preferred_working_style' => '',
                // 'current_address_area' => '',
                // 'desired_area' => '',
                // 'content' => '',
                // 'last_updated' => '',
                'isadmin' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
