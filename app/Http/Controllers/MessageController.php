<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
  public function show(Request $req){
    $user = $req->user();
    $messages = Message::where("user_id","=",$user->id)->get();
    return response()->json([
        "messages"=>$messages
    ]);
  }
  public function confirm(Request $req,$messageId){
    $user = $req->user();
    $message = Message::find($messageId);
    if($message->user_id===$user->id){
      $message->update([
        "is_confirmed"=>true
      ]);
      return response()->json([
        "message"=>$message
      ]);
    }else{
      return response()->json([
        "message"=>"あなた以外の人あてのメッセージです"
      ],Response::HTTP_CONFLICT);
    }
  }
}
