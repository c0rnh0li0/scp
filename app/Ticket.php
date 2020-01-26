<?php

namespace App;

use App\SCPModel;

class Ticket extends SCPModel
{
    protected $fillable = [
        'user_id',
        'place_id',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function place() {
        return $this->hasOne('App\User', 'place_id');
    }
}
