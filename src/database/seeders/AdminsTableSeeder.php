<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = [
            'name' => 'テスト管理人',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('abcd1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('admins')->insert($content);
    }
}
