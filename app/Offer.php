<?php

namespace App;

use App\SCPModel;

class Offer extends SCPModel
{
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
        'starts_at',
        'ends_at',
        'owner_id'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date'
    ];

    public function owner() {
        return $this->belongsTo('App\User', 'owner_id');
    }
}
