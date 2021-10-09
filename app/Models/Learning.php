<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Learning extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getNextSpan(){
        $spanArr = [1,7,14,28,56];
        return $spanArr[$this->learning_stage - 1];
    }
}
