<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fakerのインスタンスを取得
        $faker = \Faker\Factory::create('ja_JP');
        for ($i = 0; $i < 20; $i++) {
            $content = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => bcrypt('abcd1234'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('owners')->insert($content);
        }
    }
}
