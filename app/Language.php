<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'locale',
        'flag',
        'created_at',
        'updated_at',
    ];
}
