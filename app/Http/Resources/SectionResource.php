<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            "title"=>$this->title,
            "description"=>$this->description,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->updated_at,
            "count_questions"=>$this->countQuestions(),
            "posted_by"=>$this->user_id,
            "series"=>$this->series
        ];
    }
}
