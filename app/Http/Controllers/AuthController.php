<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CurrentUserResource;
use Exception;

class AuthController extends Controller
{
    public function register(Request $req){
        try{
            $user = User::create([
                "first_name"=>$req->input("first_name"),
                "last_name"=>$req->input("last_name"),
                "email"=>$req->input("email"),
                "password"=>Hash::make($req->input("password")),
            ]);
            return response(["user"=>new CurrentUserResource($user)],Response::HTTP_CREATED);
        }catch(Exception $e){
            if($e->errorInfo[0]==="23000"){
                return response()->json([
                    "message"=>"this email address is already used.",
                    "error"=>$e->errorInfo
                ],Response::HTTP_CONFLICT );
            }else{
                return response()->json([
                    "message"=>"error occurred during creating a new user",
                    "error"=>$e->errorInfo
                ],Response::HTTP_BAD_REQUEST );
            }

        }
        
    }
    public function login(Request $req){
        if(!Auth::attempt($req->only("email","password"))){
            return response([
                "error" => "invalid credentials"
            ],Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $jwt = $user->createToken("token")->plainTextToken;
        $cookie = cookie(
            "jwt",$jwt, 60 * 24
        );
        return response([
            "jwt"=>$jwt,
            "user"=>new CurrentUserResource($user)
        ])->withCookie($cookie);
    }
    public function user(Request $req){
        $user = $req->user();
        return response(["user"=>new CurrentUserResource($user)]);
    }
    public function logout(){
        $cookie = Cookie::forget("jwt");
        return response([
            "jwt"=>null,
            "message" => "success"
        ])->withCookie($cookie);
    }
    public function updateInfo(Request $req){
        $user = $req->user();
        $user->update(
            $req->only("first_name",
                    "last_name",
                    "email")
        );
        return response(["user"=>new CurrentUserResource($user)],Response::HTTP_ACCEPTED);
    }
    public function updatePassword(Request $req){
        $user = $req->user();
        $user->update([
            'password' => Hash::make($req->input('password'))
        ]);
        return response(["user"=>new CurrentUserResource($user)],Response::HTTP_ACCEPTED);
    }
}
