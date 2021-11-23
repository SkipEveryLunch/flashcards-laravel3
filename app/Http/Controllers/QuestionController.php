<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Resources\QuestionResource;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class QuestionController extends Controller
{
    public function index(){
        $questions = Question::all();
        return response()->json([        "questions"=>QuestionResource::collection($questions)]);
    }
    public function show(Request $req, $id)
    {
        $question = Question::find($id);
        if($question){
            return response()->json([
                "question"=>new QuestionResource($question)
            ]);
        }else{
            return response()->json([
                "message"=>"question not found"
            ],Response::HTTP_NOT_FOUND);
        }
    }
    public function store(Request $req)
    {
        $user = $req->user();
        try{
            $question = Question::create([
                'front'=>$req->input('front'),
                'back'=>$req->input('back'),
                'section_id'=>$req->input('section_id'),
                'posted_by'=>$user->id
            ]);
            return response()->json([
                "question"=>new QuestionResource($question)
            ],Response::HTTP_CREATED);
        }catch(Exception $e){
            if($e->errorInfo[0]==="23000"){
            return response()->json([
                "message"=>"both back and front are already used."
            ],Response::HTTP_CONFLICT);
            }else{
                return response()->json([
                    "message"=>"error occurred during creating a new question",
                    "error"=>$e->errorInfo
                ],Response::HTTP_BAD_REQUEST );
            }
        }
    }
    public function update(Request $req,$id){
        $question = Question::find($id);
        if($question){
            $question->update(
                $req->only("front","back","section_id")
            );
            return response()->json([
                "question"=>new QuestionResource($question)
            ],Response::HTTP_ACCEPTED);
        }else{
            return response()->json([
                "message"=>"question not found"
            ],Response::HTTP_NOT_FOUND);
        }
    }
    public function destroy($id){
        $question = Question::find($id);
        if($question){
            Question::destroy($id);
            return response(null,Response::HTTP_NO_CONTENT);
        }else{
            return response()->json([
                "message"=>"question not found"
            ],Response::HTTP_NOT_FOUND);
        }
    }
}
