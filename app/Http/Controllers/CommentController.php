<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Comment;
use App\Models\Question;

class CommentController extends Controller
{
    public function show(Request $req,$questionId){
        $user = $req->user();
        $comment = Comment::where("user_id","=",$user->id)->where("question_id","=",$questionId)->first();
        if($comment){
            return response()->json([
                "comment"=>$comment
            ]);
        }else{
            return response()->json([
                "message"=>"comment not found"
            ],Response::HTTP_NOT_FOUND);
        }
    }
    public function showSeveral(Request $req,$questionId){
        $user = $req->user();
        $question = Question::find($questionId);
        if($question->posted_by === $user->id){
            $comments = Comment::where("question_id","=",$questionId)->get();
            return response()->json([
                "commented_to"=>$question->posted_by,
                "comments"=>$comments
            ]);
        }else{
            return response()->json([
                "message"=>"you didn't post this question"
            ],Response::HTTP_BAD_REQUEST);
        }
    }
    public function store(Request $req,$questionId){
        $user = $req->user();
        $comment = Comment::where("user_id","=",$user->id)->where("question_id","=",$questionId)->first();
        if(!$comment){
            $comment = Comment::create([
                "comment_type"=>$req->input("comment_type"),
                "comment_detail"=>$req->input("comment_detail"),
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
    public function update(Request $req,$questionId){
        $user = $req->user();
        $comment = Comment::where("user_id","=",$user->id)->where("question_id","=",$questionId)->first();
        if($comment){
            $comment->update(
                $req->only("comment_type","comment_detail")
            );
            return response()->json([
                "comment"=>$comment
            ]);
        }else{
            return response()->json([
                "message"=>"not commented."
            ],Response::HTTP_NOT_FOUND);
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
