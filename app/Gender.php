<?php

namespace App;

use App\SCPModel;

class Gender extends SCPModel
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const GENDER_UNDEFINED = 3;

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at',
    ];
}
