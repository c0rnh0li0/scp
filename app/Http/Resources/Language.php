<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Language extends JsonResource
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
            'locale' => $this->locale,
            'flag' => $this->flag,
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
        ];
    }
}
