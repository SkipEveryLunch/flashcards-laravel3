<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\User;
use App\Models\Question;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = Section::factory(4)->create();
        foreach($sections as $section){
            $questions = Question::factory(30)->create([
                "section_id" => $section->id
            ]);
        }
    }
}