<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractLengthList extends JsonResource
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
            'name' => $this->name,
            'duration' => $this->duration,
            'duration_label' => $this->duration . ' Months',
            'price' => $this->price,
            'price_label' => $this->price . ' ' . $this->valute->sign,
            'valute_id' => $this->valute_id,
            'valute' => $this->valute,
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
            'deleted_at' => ($this->deleted_at ? $this->deleted_at->format('d F, Y') : ''),
        ];
    }
}
