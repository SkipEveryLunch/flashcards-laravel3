<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ["password"];
    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function questions(){
        return $this->belongsToMany(Question::class, "learnings")->withPivot("next_period","id");
    }
    public function sections(){
        return $this->belongsToMany(Section::class, "section_restrictions")->withPivot("next_assignment","id");
    }    
    public function unconfirmedMessages(){
        return Message::where("is_confirmed","=",false)->where("user_id","=",$this->id)->count();
    }
    public function getNextAssignment($sectionId){
        $section = $this->sections->where('id', '=', $sectionId)->first();
        if($section){
            return $section->pivot->next_assignment;
        }else{
            return null;
        }
    }
}
