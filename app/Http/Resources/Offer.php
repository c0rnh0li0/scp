<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use App\UserDetail;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as OwnerResource;
use App\Http\Resources\UserDetails as OwnerDetailResource;

class Offer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ownerDetails = UserDetail::where('user_id', $this->owner->id)->get();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'promo_image' => $this->promo_image,
            'owner_id' => $this->owner_id,
            'owner' => new OwnerResource($this->owner),
            'owner_details' => new OwnerDetailResource($ownerDetails[0]),
            'real_price' => $this->real_price,
            'offered_price' => $this->offered_price,
            'include_global' => $this->include_global,
            'featured' => $this->featured,
            'notes' => $this->notes,
            'modified_by' => $this->modified_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
            'deleted_at' => ($this->deleted_at ? $this->deleted_at->format('d F, Y') : '')
        ];
    }
}
