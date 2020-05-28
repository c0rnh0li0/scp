<?php

namespace App;

use App\SCPModel;

class Blog extends SCPModel
{
    protected $fillable = [
        'title',
        'post_text',
        'promo_image',
        'category_id',
        'subcategory_id',
        'created_by',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at',
    ];

    public function author() {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function category() {
        return $this->belongsTo('App\BlogCategory', 'category_id');
    }

    public function subcategory() {
        return $this->belongsTo('App\BlogCategory', 'subcategory_id');
    }
}
