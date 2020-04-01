<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'short_description',
        'long_description',
        'promo_image',
        'real_price',
        'offered_price',
        'include_global',
        'featured',
        'notes',
        'owner_id'
    ];

    public function owner() {
        return $this->belongsTo('App\User', 'owner_id');
    }
}
