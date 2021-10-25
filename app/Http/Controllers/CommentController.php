<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $req,$questionId){
        $user = $req->user();
        $comment = Comment::where("user_id","=",$user->id)->where("question_id","=",$questionId)->first();
        if(!$comment){
            $comment = Comment::create([
                "type"=>$req->input("type"),
                "detail"=>$req->input("detail"),
                "user_id"=>$user->id,
                "question_id"=>$questionId,
            ]);
            return response()->json([
                "comment"=>$comment
            ]);
        }else{
            return response()->json([
                "message"=>"already commented."
            ],Response::HTTP_CONFLICT);
        }
    }
    public function destroy(Request $req,$questionId){
        $user = $req->user();
        $comment = Comment::where("user_id","=",$user->id)->where("question_id","=",$questionId)->first();
        if($comment){
            Comment::destroy($comment->id);
            return response()->json(null,Response::HTTP_NO_CONTENT);
        }else{
            return response()->json([
                "message"=>"not commented yet."
            ],Response::HTTP_CONFLICT);
        }
    }
}
