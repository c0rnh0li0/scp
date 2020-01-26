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

    public function modifiedBy() {
        return $this->hasOne('App\User', 'modified_by');
    }

    public function deletedBy() {
        return $this->hasOne('App\User', 'deleted_by');
    }
}
