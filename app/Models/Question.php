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
    public function getLikedByAttribute(){
        return Favorite::where("type","=","like")->where("question_id","=",$this->id)->pluck("user_id");
    }
    public function getDislikedByAttribute(){
        return Favorite::where("type","=","dislike")->where("question_id","=",$this->id)->pluck("user_id");
    }
}