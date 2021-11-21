<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            "id"=>$this->id,
            "comment_type"=>$this->comment_type,
            "comment_detail"=>$this->comment_detail,
            "user_id"=>$this->user_id,
            "question_id"=>$this->question_id,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->updated_at,
        ];
    }
}
