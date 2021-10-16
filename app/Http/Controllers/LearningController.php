<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\User;
use App\Models\Question;
use App\Models\Learning;
use App\Models\SectionRestriction;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\QuestionResource;

class LearningController extends Controller
{
    public function newQuestions(Request $req,$id)
    {
        $numOfQ = 5;
        $user = $req->user();
        if($user && $user->getNextAssignment($id)>date("Y-m-d")){
            return response()->json(["message"=>"next assignment isn't yet"]);
        }else{
            $questions = Question::inRandomOrder()->whereDoesntHave('users', function($q)use($user){
                $q->where('user_id', '=', $user->id);
            })->where("section_id","=",$id)->take($numOfQ)->get();
            return response()->json([
                "questions"=>            QuestionResource::collection($questions)
            ]);

        }
    }
    public function answerQuestions(Request $req,$sectionId)
    {
        $user = $req->user();
        $qIds = $req->input('question_ids');
        $result = [];

        if($user->getNextAssignment($sectionId)){
            $sectionRestriction = SectionRestriction::where("user_id","=",$user->id)->where("section_id","=",$sectionId)->first();
            $sectionRestriction->update([
                "next_assignment"=>date('Y-m-d', strtotime('+1 day'))
            ]);
        }else{
            SectionRestriction::create([
                "user_id"=>$user->id,
                "section_id"=>$sectionId,
                "next_assignment"=>date('Y-m-d', strtotime('+1 day'))
            ]);
        }
        
        foreach($qIds as $qId){
            $learning = Learning::create([
                "user_id"=>$user->id,
                "question_id"=>$qId,
                "next_period"=>date('Y-m-d', strtotime('+1 day')),
            ]);
            array_push($result, $learning);
        }
        return response()->json([
            "learnings"=>$result
        ]);
    }
    public function reviewQuestions(Request $req,$id)
    {
        $user = $req->user();
        $questions = $user->questions->where("section_id","=",$id)->filter(function($q){
            return $q->pivot->next_period <= date("Y-m-d");
        })->values();
        return response()->json([
            "questions"=>QuestionResource::collection($questions)
        ]);
    }
    public function answerReviews(Request $req){
        $qIds = $req->input('question_ids');
        $user = $req->user();
        $result = [];
        foreach($qIds as $qId){
            $learning = Learning::where("user_id",$user->id)->where("question_id",$qId)->first();
            $learning->update([
                "next_period"=>date("Y-m-d",strtotime("+" . $learning->getNextSpan() . " day")),
                "learning_stage"=>$learning->learning_stage < 5 ?
                $learning->learning_stage + 1 : 5
            ]);
            array_push($result, $learning);
        }
        return response()->json([
            "learnings"=>$result
        ]);
    }
    public function test(Request $req,$id)
    {
        $user = $req->user();
        // $date = date("Y-m-d",strtotime('+1 day'));
        // $sectionRestriction = SectionRestriction::where("user_id","=",$user->id)->where("section_id","=",$id)->first();
        // $sectionRestriction->update([
        //     "next_assignment"=>$date
        // ]);

        $next_assignment = $user->getNextAssignment($id);

        // SectionRestriction::create([
        //     "user_id"=>$user->id,
        //     "section_id"=>$id,
        //     "next_assignment"=>date("Y-m-d")
        // ]);
        return response()->json([
            "section"=>$next_assignment
        ]);
    }
}