<?php

namespace App;

use App\SCPModel;

class Location extends SCPModel
{
    protected $fillable = [
        'longitude',
        'latitude',
        'address',
        'number',
        'city_id',
        'modified_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function city() {
        return $this->hasOne('App\City', 'city_id');
    }
}
