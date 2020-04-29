<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends SCPModel
{
    protected $fillable = [
        'name',
        'parent_id',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at',
    ];

    public function parent() {
        return $this->belongsTo('App\Category', 'parent_id');
    }

    public function children() {
        return $this->hasMany('App\Category', 'parent_id');
    }
}
