<?php

namespace App;

use App\SCPModel;

class Country extends SCPModel
{
    protected $fillable = [
        'code',
        'name',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at',
    ];

    public function cities() {
        return $this->hasMany(City::class);
    }
}
