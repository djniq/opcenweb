<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();   
        DB::table('users')->insert([
            'first_name' => 'Superadmin',
            'last_name' => 'User',
            'health_facility_id' => 1, 
            'username' => 'superadmin',
            'email' => Str::random(10).'@gmail.com',
            'contact_no' => $faker->numerify('9#########'),
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'status' => 1,
            'created_by' => 1
        ]);

        DB::table('users')->insert([
            'first_name' => 'Hfadmin',
            'last_name' => 'User',
            'health_facility_id' => 1,
            'username' => 'hfadmin',
            'email' => Str::random(10).'@gmail.com',
            'contact_no' => $faker->numerify('9#########'),
            'password' => Hash::make('password'),
            'role' => 'hfadmin',
            'status' => 1,
            'created_by' => 1
        ]);

        DB::table('users')->insert([
            'first_name' => 'Opcen',
            'last_name' => 'User',
            'health_facility_id' => 1,
            'username' => 'opcen',
            'email' => Str::random(10).'@gmail.com',
            'contact_no' => $faker->numerify('9#########'),
            'password' => Hash::make('password'),
            'role' => 'opcen',
            'status' => 1,
            'created_by' => 1
        ]);

        DB::table('users')->insert([
            'first_name' => 'Emt',
            'last_name' => 'User',
            'health_facility_id' => 1,
            'username' => 'emt',
            'contact_no' => $faker->numerify('9#########'),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'emt',
            'status' => 1,
            'created_by' => 1
        ]);
    }
}
