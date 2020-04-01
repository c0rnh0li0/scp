<?php

namespace App;

use App\SCPModel;

class Ticket extends SCPModel
{
    protected $fillable = [
        'user_id',
        'place_id',
        'used',
        'amount',
        'qr_code',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function offer() {
        return $this->belongsTo('App\Offer', 'offer_id');
    }
}
