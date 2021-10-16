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
    public function getFavedByAttribute(){
        return Favorite::where("type","=","fav")->where("question_id","=",$this->id)->pluck("user_id");
    }
    public function getUnfavedByAttribute(){
        return Favorite::where("type","=","unfav")->where("question_id","=",$this->id)->pluck("user_id");
    }
}