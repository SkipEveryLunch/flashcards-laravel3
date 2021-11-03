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
}
