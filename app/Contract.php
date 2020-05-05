<?php

namespace App;

use App\SCPModel;

class Contract extends SCPModel
{
    protected $fillable = [
        'owner_id',
        'contract_length_id',
        'paid',
        'valid',
        'reminders',
        'start_at',
        'expires_at',
        'created_at',
        'updated_at'
    ];

    public function length() {
        return $this->belongsTo('App\ContractLength', 'contract_length_id');
    }

    public function owner() {
        return $this->belongsTo('App\User', 'owner_id');
    }
}
