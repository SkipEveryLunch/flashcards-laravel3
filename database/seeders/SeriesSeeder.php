<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Series;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            "前置詞・名詞",
            "単位・度合",
            "時・条件",
            "仮定法"
        ];
        foreach($names as $name){
            Series::factory()->create([
                "name"=>$name
            ]);
        }
    }
}
