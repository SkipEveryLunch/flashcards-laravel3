<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\Question;
use App\Models\Learning;
use App\Models\User;

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
            foreach($questions as $question){
                $users = User::all()->toArray();
               $randUsers = rand_els($users);
                foreach($randUsers as $user){
                    Learning::factory()->create([
                        "question_id"=>$question->id,
                        "user_id"=>$user["id"],
                    ]);
                }
            }
        }
    }
}

function rand_els($arr){
    // $randInt = mt_rand(1,4);
    // dump($randInt);
    $randIds = array_rand($arr,3);
    $randUsers = [];
    foreach($randIds as $id){
        array_push($randUsers,$arr[$id]);
    }
    return $randUsers;
}