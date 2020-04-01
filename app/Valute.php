<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Valute extends Model
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
