<?php

namespace App;

use App\SCPModel;

class Gender extends SCPModel
{
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at',
    ];
}
