<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            "東京都",
            "大阪府",
            "福岡県"
        ];

        foreach ($contents as $content) {
            DB::table('areas')->insert([
                'area' => $content,
            ]);
        }
    }
}
