<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Comment;
use App\Models\Question;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\DB;
use App\Events\CommentSent;

class CommentController extends Controller
{
    public function show(Request $req,$questionId){
        $user = $req->user();
        $comment = Comment::where("user_id","=",$user->id)->where("question_id","=",$questionId)->first();
        
        if($comment){
            return response()->json([
                "comment"=>new CommentResource($comment),
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
        if($question->posted_by === $user->id || $user->role->id === 1){
            $comments = Comment::where("question_id","=",$questionId)->get();
            $comment_types = Comment::where("question_id","=",$questionId)
            ->join('comment_types','comment_types.id','=','comments.comment_type_id')->select('comment_types.id','comment_types.name',DB::raw('count(*) as count'))->groupBy("comment_type_id")
            ->get();
            return response()->json([
                "commented_to"=>$question->posted_by,
                "comments"=>CommentResource::collection($comments),"comment_types"=>$comment_types
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
            $question = Question::find($questionId);
            if($question->posted_by !== $user->id){
                $comment = Comment::create([
                    "comment_type_id"=>$req->input("comment_type_id"),
                    "comment_detail"=>$req->input("comment_detail"),
                    "user_id"=>$user->id,
                    "question_id"=>$questionId,
                ]);            
                event(new CommentSent($questionId));
                return response()->json([
                    "comment"=>new CommentResource($comment)
                ]);
            }else{
                return response()->json([
                    "message"=>"this question is posted by you."
                ],Response::HTTP_CONFLICT);
            }
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
                $req->only("comment_type_id","comment_detail")
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
    public function destroyForAdmin(Request $req,$commentId){
        $user = $req->user();
        if($user->role->id === 1){
            Comment::destroy($commentId);
            return response()->json(null,Response::HTTP_NO_CONTENT);
        }else{
            return response()->json([
                "message"=>"you aren't an admin."
            ],Response::FORBIDDEN);
        }
    }
}
