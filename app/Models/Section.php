<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Series;

class Section extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function questions(){
        return $this->hasMany(Question::class);
    }
    public function users(){
        return $this->belongsToMany(User::class, "section_restrictions")->withPivot("next_assignment","id");
    }
    public function countQuestions(){
        return Question::where("section_id","=",$this->id)->count();
    }
    function getLearnings($user){
        $questionsIds = Question::where("section_id","=",$this->id)->pluck("id")->toArray();
        return Learning::where("user_id","=",$user->id)->whereIn("question_id",$questionsIds)->get();
    }
    public function getCompleteRate($user){
        $completedScore = $this->getLearnings($user)->sum("learning_stage");
        $goal = $this->countQuestions() * 5;
        if($goal == 0){
            return 0;
        }
            return $completedScore / $goal;
    }
    public function series(){
        return $this->belongsTo(Series::class);
    }
}