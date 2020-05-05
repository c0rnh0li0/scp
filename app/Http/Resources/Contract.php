<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\ContractLength as ContractLengthResource;

class Contract extends JsonResource
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
            'owner_id' => $this->owner_id,
            'owner' => new UserResource($this->owner),
            'contract_length_id' => $this->contract_length_id,
            'length' => new ContractLengthResource($this->length),
            'price' => $this->price,
            'valute_id' => $this->valute_id,
            'valute' => $this->valute,
            'paid' => $this->paid,
            'valid' => $this->valid,
            'start_at' => $this->start_at,
            'expires_at' => $this->expires_at,
            'reminders' => $this->reminders,
            'start_at_list' => ($this->start_at ? Carbon::parse($this->start_at)->format('d F, Y') : ''),
            'expires_at_list' => ($this->expires_at ? Carbon::parse($this->expires_at)->format('d F, Y') : ''),
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
            'deleted_at' => ($this->deleted_at ? $this->deleted_at->format('d F, Y') : ''),
        ];
    }
}
