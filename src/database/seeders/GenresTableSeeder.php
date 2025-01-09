<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            "寿司",
            "焼肉",
            "居酒屋",
            "イタリアン",
            "ラーメン"
        ];

        foreach ($contents as $content) {
            DB::table('genres')->insert([
                'genre' => $content,
            ]);
        }
    }
}
