<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrentUserResource extends JsonResource
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
            "first_name"=>$this->first_name,
            "last_name"=>$this->last_name,
            "email"=>$this->email,
            "unconfirmed_messages"=>$this->unconfirmedMessages(),
            "role"=>new RoleResource($this->role),
            "created_at"=>$this->created_at->format('Y-m-d'),
            "updated_at"=>$this->updated_at->format('Y-m-d'),
        ];
    }
}
