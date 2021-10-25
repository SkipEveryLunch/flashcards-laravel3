<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Question;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = Question::all()->toArray();
        $users = User::inRandomOrder(5)->get()->toArray();
        $favOrUnfav = ["fav","unfav"];
        foreach($questions as $question){
            foreach($users as $user){
                if(rand_judge(2)){
                    Comment::factory()->create([
                        "user_id"=>$user["id"],
                        "question_id"=>$question["id"]
                    ]);
                }
            }
        }
    }
}
function rand_judge($div){
    $rand = rand(1,10);
    return $rand > 10/$div ? true:false;
}