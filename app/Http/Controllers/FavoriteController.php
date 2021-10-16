<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function store(Request $req,$questionId){
        $user = $req->user();
        $fav = Favorite::where("user_id","=",$user->id)->where("question_id","=",$questionId)->first();
        if(!$fav){
            $fav = Favorite::create([
                "type"=>$req->input("type"),
                "user_id"=>$user->id,
                "question_id"=>$questionId,
            ]);
            return response()->json([
                "fav"=>$fav
            ]);
        }else{
            return response()->json([
                "message"=>"already faved or unfaved."
            ],Response::HTTP_CONFLICT);
        }
    }
    public function destroy(Request $req,$questionId){
        $user = $req->user();
        $fav = Favorite::where("user_id","=",$user->id)->where("question_id","=",$questionId)->first();
        if($fav){
            Favorite::destroy($fav->id);
            return response()->json(null,Response::HTTP_NO_CONTENT);
        }else{
            return response()->json([
                "message"=>"not faved or unfaved yet."
            ],Response::HTTP_CONFLICT);
        }
    }
}
