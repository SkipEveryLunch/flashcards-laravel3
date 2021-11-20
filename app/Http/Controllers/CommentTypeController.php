<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentType;

class CommentTypeController extends Controller
{
    public function index(){
        $comment_types = CommentType::all();
        return response()->json([
            "comment_types"=>$comment_types
        ]);
    }
}
