<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'description' => $this->description,
            'picture' => $this->picture,
            'user' => $this->user,
            'user_type' => $this->user_type,
            'gender' => $this->gender,
            'location' => $this->location,
            'modified_by' => $this->modified_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
            'deleted_at' => ($this->deleted_at ? $this->deleted_at->format('d F, Y') : ''),
            'is_company' => $this->is_company
        ];
    }
}
