<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Section;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use App\Http\Resources\SectionResource;
use App\Http\Resources\QuestionResource;

class SectionController extends Controller
{

    public function index(){
        $sections = Section::all();
        $series = Series::has("sections")->get();
        return response()->json([
            "sections"=>SectionResource::collection($sections),
            "series"=>$series
        ]);
    }
    public function show(Request $req,$id)
    {
        $user = $req->user();
        $section = Section::find($id);
        if($section){
            $section->count_questions = $section->countQuestions();
            $section->complete_rate = $section->getCompleteRate($user);
            $questions = $section->questions()->get()->map(function($q)use($user){
                $learning = $q->getLearning($user);
                return [
                    "id"=> $q->id,
                    "front"=> $q->front,
                    "back"=> $q->back,
                    "section_id"=> $q->section_id,
                    "posted_by"=> $q->posted_by,
                    "commented_by"=> $q->commented_by,
                    "created_at"=> $q->created_at,
                    "updated_at"=> $q->updated_at,
                    "next_period" => $learning?$learning->next_period:"",
                    "learning_stage" => $learning?$learning->learning_stage:""
                ];
            });
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
        $user = $req->user();
        try{
            $section = Section::create([
                'title'=>$req->input('title'),
                'description'=>$req->input('description'),
                "user_id"=>$user->id,
                'series_id'=>$req->input('series_id'),
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