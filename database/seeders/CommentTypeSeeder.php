<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommentType;

class CommentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CommentType::factory()->create([
            "name"=>"複雑・長すぎる",
        ]);
        CommentType::factory()->create([
            "name"=>"セクションにそぐわない",
        ]);
        CommentType::factory()->create([
            "name"=>"内容が攻撃的",
        ]);
        CommentType::factory()->create([
            "name"=>"文法的に不自然",
        ]);
    }
}
