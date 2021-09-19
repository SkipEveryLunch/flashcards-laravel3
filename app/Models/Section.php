<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

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
}