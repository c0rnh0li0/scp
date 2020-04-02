<?php

namespace App\Http\Resources;

use App\Http\Resources\Country as CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\City as CityResource;
use App\Http\Resources\Category as CategoryResource;

class Location extends JsonResource
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
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'address' => $this->address,
            'number' => $this->number,
            'city_id' => $this->city_id,
            'city' => new CityResource($this->city),
            'country_id' => $this->country_id,
            'country' => new CountryResource($this->country),
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'category' => new CategoryResource($this->category),
            'subcategory' => new CategoryResource($this->subcategory),
            'modified_by' => ($this->modified_by ? $this->modified_by : null),
            'deleted_by' => ($this->deleted_by ? $this->deleted_by : null),
            'deleted_at' => ($this->deleted_at ? $this->deleted_at->format('d F, Y') : ''),
            'created_at' => ($this->created_at ? $this->created_at->format('d F, Y') : ''),
            'updated_at' => ($this->updated_at ? $this->updated_at->format('d F, Y') : ''),
        ];
    }
}
