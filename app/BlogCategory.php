<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
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
        return $this->belongsTo('App\BlogCategory', 'parent_id');
    }

    public function children() {
        return $this->hasMany('App\BlogCategory', 'parent_id');
    }
}
