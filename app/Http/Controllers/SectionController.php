<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use App\Http\Resources\SectionResource;

class SectionController extends Controller
{

    public function index(){
        $sections = Section::all();
        return response()->json([
            "sections"=>SectionResource::collection($sections)
        ]);
    }
    public function show(Request $req,$id)
    {
        $user = $req->user();
        $section = Section::find($id);
        if($section){
            $section->count_questions = $section->countQuestions();
            $section->complete_rate = $section->getCompleteRate($user);
            $questions = Question::where("section_id","=",$id)->get();
            foreach ($questions as $question) {
                $learning = $question->getLearning($user);
                if($learning){
                    $question->next_period = $learning->next_period;
                    $question->learning_stage = $learning->learning_stage;
                }
            }
            $section->questions = $questions;
            return response()->json([
                "section"=>$section
            ]);
        }else{
            return response()->json([
                "message"=>"section not found"
            ],Response::HTTP_NOT_FOUND);
        }
    }
    public function store(Request $req)
    {
        try{
            $section = Section::create([
                'title'=>$req->input('title'),
            ]);
            return response()->json([
                "section"=>$section
            ],Response::HTTP_CREATED);
        }catch(Exception $e){
            if($e->errorInfo[0]==="23000"){
            return response()->json([
                "message"=>"title is already used."
            ],Response::HTTP_CONFLICT);
            }else{
                return response()->json([
                    "message"=>"error occurred during creating a new section",
                    "error"=>$e->errorInfo
                ],Response::HTTP_BAD_REQUEST );
            }
        }
    }
    public function update(Request $req,$id)
    {
        $section = Section::find($id);
        if($section){
            $section->update(
                $req->only("title")
            );
            return response()->json([
                "section"=>$section
            ],Response::HTTP_ACCEPTED);
        }else{
            return response()->json([
                "message"=>"section not found"
            ],Response::HTTP_NOT_FOUND);
        }
    }
    public function destroy($id){
        $section = Section::find($id);
        if($section){
            Section::destroy($id);
            return response(null,Response::HTTP_NO_CONTENT);
        }else{
            return response()->json([
                "message"=>"section not found"
            ],Response::HTTP_NOT_FOUND);
        }
    }
}