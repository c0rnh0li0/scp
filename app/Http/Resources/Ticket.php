<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserDetails as PlaceDetailResource;
use App\Http\Resources\Offer as OfferDetailResource;

class Ticket extends JsonResource
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
            'user' => new UserResource($this->user),
            'offer' => new OfferDetailResource($this->offer),
            'used' => $this->used,
            'amount' => $this->amount,
            'qr_code' => $this->qr_code,
            'modified_by' => $this->modified_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
            'deleted_at' => ($this->deleted_at ? $this->deleted_at->format('d F, Y') : '')
        ];
    }
}
