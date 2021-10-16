<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function users(){
        return $this->belongsToMany(User::class, "learnings");
    }
    public function getLearning($user){
        return Learning::where("user_id","=",$user->id)->where("question_id","=",$this->id)->first();
    }
    public function getFavsAttribute(){
        return Favorite::where("type","=","fav")->where("question_id","=",$this->id)->count();
    }
    public function getUnfavsAttribute(){
        return Favorite::where("type","=","unfav")->where("question_id","=",$this->id)->count();
    }
}