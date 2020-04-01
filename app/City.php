<?php

namespace App;

use App\SCPModel;

class City extends SCPModel
{
    protected $fillable = [
        'name',
        'country_id',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at',
    ];

    public function country() {
        return $this->belongsTo(Country::class);
    }
}
