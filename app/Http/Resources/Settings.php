<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Settings extends JsonResource
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
            'site_name' => $this->site_name,
            'site_logo' => $this->site_logo,
            'frontpage_template' => $this->frontpage_template,
            'phone' => $this->phone,
            'address' => $this->address,
            'email' => $this->email,
            'contract_check' => $this->contract_check,
            'language_id' => $this->language_id,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : '')
        ];
    }
}
