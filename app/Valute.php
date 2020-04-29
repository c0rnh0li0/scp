<?php

namespace App;

use App\SCPModel;

class Valute extends SCPModel
{
    protected $fillable = [
        'name',
        'sign',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at',
    ];
}
