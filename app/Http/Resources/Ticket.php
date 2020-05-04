<?php

namespace App\Http\Resources;

use App\UserDetail;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserDetails as UserDetailResource;
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
        $userDetails = UserDetail::where('user_id', $this->user->id)->get();

        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'user_details' => new UserDetailResource($userDetails[0]),
            'offer' => new OfferDetailResource($this->offer),
            'used' => $this->used,
            'amount' => $this->amount,
            'qr_code' => $this->qr_code,
            'used_at' => ($this->used_at ? $this->used_at->format('d F, Y') : ''),
            'modified_by' => $this->modified_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
            'deleted_at' => ($this->deleted_at ? $this->deleted_at->format('d F, Y') : '')
        ];
    }
}
