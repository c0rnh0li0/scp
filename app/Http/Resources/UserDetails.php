<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Location as LocationResource;

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
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'description' => $this->description,
            'picture' => $this->picture ? $this->picture : 'avatar_default.png',
            'website' => $this->website,
            'user' => new UserResource($this->user),
            'type' => $this->type,
            'gender' => $this->gender,
            'location' => new LocationResource($this->location),
            'valute' => $this->valute,
            'modified_by' => $this->modified_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
            'deleted_at' => ($this->deleted_at ? $this->deleted_at->format('d F, Y') : ''),
            'is_company' => $this->is_company
        ];
    }
}
