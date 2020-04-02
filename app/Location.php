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
        'country_id',
        'category_id',
        'subcategory_id',
        'modified_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function city() {
        return $this->belongsTo('App\City', 'city_id');
    }

    public function country() {
        return $this->belongsTo('App\Country', 'country_id');
    }

    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function subcategory() {
        return $this->belongsTo('App\Category', 'subcategory_id');
    }
}
