<?php

namespace App;

use App\SCPModel;

class ContractLength extends SCPModel
{
    protected $fillable = [
        'name',
        'duration',
        'price',
        'valute_id',
        'updated_at',
        'modified_by'
    ];

    public function valute() {
        return $this->belongsTo('App\Valute', 'valute_id');
    }
}
