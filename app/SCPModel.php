<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SCPModel extends Model
{
    protected $fillable = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}
