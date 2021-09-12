<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class SectionController extends Controller
{

    public function index(){
        $sections = Section::all();
        return response()->json([
            "sections"=>$sections
        ]);
    }
    public function show($id)
    {
        $section = Section::find($id);
        if($section){
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
        $section = Section::create([
            'title'=>$req->input('title'),
        ]);
        return response()->json([
            "section"=>$section
        ],Response::HTTP_CREATED);
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